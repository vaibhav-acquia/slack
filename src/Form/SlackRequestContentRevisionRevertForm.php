<?php

namespace Drupal\slack\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\slack\Entity\SlackRequestContentInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for reverting a Slack request content revision.
 *
 * @ingroup slack
 */
class SlackRequestContentRevisionRevertForm extends ConfirmFormBase {

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
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->slackRequestContentStorage = $container->get('entity_type.manager')->getStorage('slack_request_content');
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'slack_request_content_revision_revert_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to revert to the revision from %revision-date?', [
      '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime()),
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
    return $this->t('Revert');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return '';
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
    // The revision timestamp will be updated when the revision is saved. Keep
    // the original one for the confirmation message.
    $original_revision_timestamp = $this->revision->getRevisionCreationTime();

    $this->revision = $this->prepareRevertedRevision($this->revision, $form_state);
    $this->revision->revision_log = $this->t('Copy of the revision from %date.', [
      '%date' => $this->dateFormatter->format($original_revision_timestamp),
    ]);
    $this->revision->save();

    $this->getLogger('content')->notice('Slack request content: reverted %title revision %revision.', [
      '%title' => $this->revision->label(),
      '%revision' => $this->revision->getRevisionId(),
    ]);
    $this->messenger()->addMessage($this->t('Slack request content %title has been reverted to the revision from %revision-date.', [
      '%title' => $this->revision->label(),
      '%revision-date' => $this->dateFormatter->format($original_revision_timestamp),
    ]));
    $form_state->setRedirect(
      'entity.slack_request_content.version_history',
      ['slack_request_content' => $this->revision->id()]
    );
  }

  /**
   * Prepares a revision to be reverted.
   *
   * @param \Drupal\slack\Entity\SlackRequestContentInterface $revision
   *   The revision to be reverted.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\slack\Entity\SlackRequestContentInterface
   *   The prepared revision ready to be stored.
   */
  protected function prepareRevertedRevision(SlackRequestContentInterface $revision, FormStateInterface $form_state) {
    $revision->setNewRevision();
    $revision->isDefaultRevision(TRUE);
    $revision->setRevisionCreationTime(REQUEST_TIME);

    return $revision;
  }

}
