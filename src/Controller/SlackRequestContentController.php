<?php

namespace Drupal\slack\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\slack\Entity\SlackRequestContentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SlackRequestContentController.
 *
 *  Returns responses for Slack request content routes.
 */
class SlackRequestContentController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Slack request content revision.
   *
   * @param int $slack_request_content_revision
   *   The Slack request content revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($slack_request_content_revision) {
    $slack_request_content = $this->entityTypeManager()->getStorage('slack_request_content')
      ->loadRevision($slack_request_content_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('slack_request_content');

    return $view_builder->view($slack_request_content);
  }

  /**
   * Page title callback for a Slack request content revision.
   *
   * @param int $slack_request_content_revision
   *   The Slack request content revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($slack_request_content_revision) {
    $slack_request_content = $this->entityTypeManager()->getStorage('slack_request_content')
      ->loadRevision($slack_request_content_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $slack_request_content->label(),
      '%date' => $this->dateFormatter->format($slack_request_content->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Slack request content.
   *
   * @param \Drupal\slack\Entity\SlackRequestContentInterface $slack_request_content
   *   A Slack request content object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SlackRequestContentInterface $slack_request_content) {
    $account = $this->currentUser();
    $slack_request_content_storage = $this->entityTypeManager()->getStorage('slack_request_content');

    $langcode = $slack_request_content->language()->getId();
    $langname = $slack_request_content->language()->getName();
    $languages = $slack_request_content->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $slack_request_content->label()]) : $this->t('Revisions for %title', ['%title' => $slack_request_content->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all slack request content revisions") || $account->hasPermission('administer slack request content entities')));
    $delete_permission = (($account->hasPermission("delete all slack request content revisions") || $account->hasPermission('administer slack request content entities')));

    $rows = [];

    $vids = $slack_request_content_storage->revisionIds($slack_request_content);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\slack\SlackRequestContentInterface $revision */
      $revision = $slack_request_content_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $slack_request_content->getRevisionId()) {
          $link = $this->l($date, new Url('entity.slack_request_content.revision', [
            'slack_request_content' => $slack_request_content->id(),
            'slack_request_content_revision' => $vid,
          ]));
        }
        else {
          $link = $slack_request_content->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.slack_request_content.translation_revert', [
                'slack_request_content' => $slack_request_content->id(),
                'slack_request_content_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.slack_request_content.revision_revert', [
                'slack_request_content' => $slack_request_content->id(),
                'slack_request_content_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.slack_request_content.revision_delete', [
                'slack_request_content' => $slack_request_content->id(),
                'slack_request_content_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['slack_request_content_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
