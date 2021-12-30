<?php

namespace Drupal\slack\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Slack request content revision.
 *
 * @ingroup slack
 */
class SlackRequestContentRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Slack request content revision.
   *
   * @var \Drupal\slack\Entity\SlackRequestContentInterface
   */
  protected $revision;

  /**
   * The Slack request content storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $slackRequestContentStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->slackRequestContentStorage = $container->get('entity_type.manager')->getStorage('slack_request_content');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'slack_request_content_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.slack_request_content.version_history', ['slack_request_content' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $slack_request_content_revision = NULL) {
    $this->revision = $this->SlackRequestContentStorage->loadRevision($slack_request_content_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->SlackRequestContentStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Slack request content: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Slack request content %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.slack_request_content.canonical',
       ['slack_request_content' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {slack_request_content_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.slack_request_content.version_history',
         ['slack_request_content' => $this->revision->id()]
      );
    }
  }

}
