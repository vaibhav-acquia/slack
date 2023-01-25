<?php

namespace Drupal\slack\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use GuzzleHttp\Client;

/**
 * Class SlackAppSettingsForm.
 *
 * @ingroup slack
 */
class SlackAppSettingsForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'slackapp_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }

  /**
   * Defines the settings form for Slack App entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

//    $app_id = '1';
//    $chat_api_plugin_manager= \Drupal::service('plugin.manager.slack_api.processor');
////    $asdasd = $chat_api_plugin_manager->getOptionsList();
//    /* @var $plugin \Drupal\activity_creator\Plugin\ActivityContextBase */
//    $plugin = $chat_api_plugin_manager->createInstance('slack_channelsapi');
//   // $web_api = $plugin->getRecipients($data, $data['last_uid'], 0);
//    $chat_api = $plugin->initMethods($app_id);
////    $response = $chat_api->channels->list();
////    $response = $chat_api->chat->postMessage([
////      "channel" => "CNK6PLWSZ",
////      "text" => "test message",
////    ]);
//    $chat_api->opts['headers']['Content-type'] = 'application/json; charset=utf-8';
//    $opts = [
//      'json' => ['channel' => 'CDCMU6XL3', 'text' => 'testidasdasdasdng']];
//    $client = new Client($chat_api->opts);
//    $response = $client->request('POST', 'https://slack.com/api/chat.postMessage',
//      $opts);
//
//    $a = $response->getBody()->getContents();
//
//    $form['slackapp_settings']['#markup'] = 'Settings form for Slack App entities. Manage field settings here.';
//    return $form;
  }

}
