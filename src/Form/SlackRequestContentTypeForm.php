<?php

namespace Drupal\slack\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SlackRequestContentTypeForm.
 */
class SlackRequestContentTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $slack_request_content_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $slack_request_content_type->label(),
      '#description' => $this->t("Label for the Slack request content type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $slack_request_content_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\slack\Entity\SlackRequestContentType::load',
      ],
      '#disabled' => !$slack_request_content_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $slack_request_content_type = $this->entity;
    $status = $slack_request_content_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Slack request content type.', [
          '%label' => $slack_request_content_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Slack request content type.', [
          '%label' => $slack_request_content_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($slack_request_content_type->toUrl('collection'));
  }

}
