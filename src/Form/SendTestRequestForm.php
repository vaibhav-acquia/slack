<?php

namespace Drupal\slack\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for sending a test message.
 *
 * @package Drupal\slack\Form
 */
class SendTestRequestForm extends FormBase {

  /**
   * Messenger service
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  /**
   * Messenger service
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Method form builder.
   *
   * @var \Drupal\slack\Service\SlackFormMethodsBuilder
   */
  protected $slackApiMethodFormBuilder;

  /**
   * Slack API send request service.
   *
   * @var \Drupal\slack\Service\SlackSendRequest
   */
  protected $slackSendRequest;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->messenger = $container->get('messenger');
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->slackApiMethodFormBuilder = $container->get('slack_api.method_form_builder');
    $instance->slackSendRequest = $container->get('slack.slack_send_request');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'slack_check_provided_api';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $slack_app = $form_state->getValue("slack_app")[0]["target_id"];
    $slack_plugin = $form_state->getValue("slack_plugin");
    $slack_method_key = $form_state->getValue("slack_methods");

    $form = $this->slackApiMethodFormBuilder->getMethodsForm($form, [
      'app' => $slack_app,
      'plugin' => $slack_plugin,
      'method' => $slack_method_key,
    ]);

    if (!empty($form['slack_plugin'])) {
      $form['slack_plugin']['#ajax'] = [
        'callback' => [$form_state->getBuildInfo()['callback_object'], 'ajaxUpdateForm'],
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'get-plugin-wrapper',
      ];
    }

    if (!empty($form['slack_methods'])) {
      $form['slack_methods']['#ajax'] = [
        'callback' => [$form_state->getBuildInfo()['callback_object'], 'ajaxUpdateForm'],
        'disable-refocus' => FALSE,
        'event' => 'change',
        'wrapper' => 'get-method-wrapper',
      ];
    }

    if (!empty($form['slack_method_fieldset'])) {
      $form['slack_method_fieldset']['send_request'] = [
        '#type' => 'button',
        '#value' => $this->t('Send Request'),
        '#name' => $this->t('sendRequest'),
        '#attributes' => [
          'class' => ['inline', 'use-ajax', 'use-ajax-submit'],
        ],
        '#ajax' => [
          'callback' => [
            $form_state->getBuildInfo()['callback_object'],
            'sendRequest'
          ],
          'wrapper' => 'get-permissions-wrapper',
        ],
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function ajaxUpdateForm(array $form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand('form.slack-check-provided-api', $form));
    return $response;
  }

  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function sendRequest(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $errors = $form_state->getErrors();
    if (!empty($form_state->getErrors())) {
      $message = '';
      foreach ($errors as $element => $error) {
        $message .= $error->__toString() . ' ';
      }
      $response->addCommand(new MessageCommand($message, NULL, ['type' => 'error']));
      $form_state->clearErrors();
      \Drupal::messenger()->deleteAll();
    }
    else {
      $slack_app = $form_state->getValue("slack_app")[0]["target_id"];
      $endpoint_key = $form_state->getValue('slack_methods');
      $slack_api = \Drupal::service('plugin.manager.slack_api')->createInstance($form_state->getValue('slack_plugin'))->getAvailableEndpoints();
      $endpoint = $slack_api[$endpoint_key];
      $params = [];

      foreach ($slack_api[$endpoint_key]['arguments'] as $key => $specs) {
        if (!empty($form_state->getValue('slack_method_' . $key))) {
          $params[$key] = $form_state->getValue('slack_method_' . $key);
        }
      }

      $this->slackSendRequest->setSlackApp($this->entityTypeManager->getStorage('slack_app')->load($slack_app));
      $body = $this->slackSendRequest->sendApiRequest($endpoint['path'], $params, $endpoint['method']);

      $message = 'Status: ' . ($body['ok'] ? 'OK' : 'ERROR') . "; ";
      $message .= $body['error'] ? 'Error: ' . $body['error'] : '';

      $response->addCommand(new MessageCommand($message, NULL, ['type' => $body['ok'] ? 'status' : 'error']));
    }

    return $response;
  }

}
