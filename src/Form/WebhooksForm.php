<?php

/**
 * @file
 * Contains Drupal\slack\Form\WebhooksForm.
 * Configures administrative settings for slack.
 */

namespace Drupal\slack\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebhooksForm.
 *
 * @package Drupal\slack\Form
 *
 * @ingroup slack
 */
class WebhooksForm extends ConfigFormBase {

  /**
   * Messenger service
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->messenger = $container->get('messenger');
    return $instance;
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'slack_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['slack.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->messenger->addMessage($this->t("A deprecated Incoming Webhooks is not recommended to use. <a href='https://api.slack.com/legacy/custom-integrations/messaging/webhooks' target='_blank'>More info</a>"), 'warning');

    $config = $this->config('slack.settings');
    $form['slack_webhook_url'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Webhook URL'),
      '#description' => $this->t('Enter your Webhook URL from an Incoming WebHooks integration. It looks like https://hooks.slack.com/services/XXXXXXXXX/YYYYYYYYY/ZZZZZZZZZZZZZZZZZZZZZZZZ'),
      '#default_value' => $config->get('slack_webhook_url'),
      '#required' => TRUE,
    );
    $form['slack_test_message'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#required' => TRUE,
    );

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Send message'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('slack.settings');
    $config->set('slack_webhook_url', $form_state->getValue('slack_webhook_url'))->save();

    $webhook = $form_state->getValue('slack_webhook_url');
    $message = $form_state->getValue('slack_test_message');
    $response = \Drupal::service('slack.slack_send_request')->sendMessage($webhook, $message);

    if (Response::HTTP_OK == $response->getStatusCode()) {
      $this->messenger->addMessage($this->t('Message was successfully sent!'));
    }
    else {
      $this->messenger->addMessage($this->t('Please check log messages for further details'), 'warning');
    }
    parent::submitForm($form, $form_state);
  }

}
