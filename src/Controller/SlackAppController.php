<?php

namespace Drupal\slack\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\slack\Entity\SlackAppInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SlackAppController.
 *
 *  Returns responses for Slack App routes.
 */
class SlackAppController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Slack App revision.
   *
   * @param int $slack_app_revision
   *   The Slack App revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($slack_app_revision) {
    $slack_app = $this->entityTypeManager()->getStorage('slack_app')
      ->loadRevision($slack_app_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('slack_app');

    return $view_builder->view($slack_app);
  }

  /**
   * Page title callback for a Slack App revision.
   *
   * @param int $slack_app_revision
   *   The Slack App revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($slack_app_revision) {
    $slack_app = $this->entityTypeManager()->getStorage('slack_app')
      ->loadRevision($slack_app_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $slack_app->label(),
      '%date' => $this->dateFormatter->format($slack_app->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Slack App.
   *
   * @param \Drupal\slack\Entity\SlackAppInterface $slack_app
   *   A Slack App object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SlackAppInterface $slack_app) {
    $account = $this->currentUser();
    $slack_app_storage = $this->entityTypeManager()->getStorage('slack_app');

    $langcode = $slack_app->language()->getId();
    $langname = $slack_app->language()->getName();
    $languages = $slack_app->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $slack_app->label()]) : $this->t('Revisions for %title', ['%title' => $slack_app->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all slack app revisions") || $account->hasPermission('administer slack app entities')));
    $delete_permission = (($account->hasPermission("delete all slack app revisions") || $account->hasPermission('administer slack app entities')));

    $rows = [];

    $vids = $slack_app_storage->revisionIds($slack_app);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\slack\SlackAppInterface $revision */
      $revision = $slack_app_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $slack_app->getRevisionId()) {
          $link = $this->l($date, new Url('entity.slack_app.revision', [
            'slack_app' => $slack_app->id(),
            'slack_app_revision' => $vid,
          ]));
        }
        else {
          $link = $slack_app->link($date);
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
              Url::fromRoute('entity.slack_app.translation_revert', [
                'slack_app' => $slack_app->id(),
                'slack_app_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.slack_app.revision_revert', [
                'slack_app' => $slack_app->id(),
                'slack_app_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.slack_app.revision_delete', [
                'slack_app' => $slack_app->id(),
                'slack_app_revision' => $vid,
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

    $build['slack_app_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
