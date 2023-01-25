<?php

namespace Drupal\slack\Service;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Messenger\Messenger;
use Drupal\slack\Core\SlackApi\SlackApiManager;

class SlackFormMethodsBuilder {

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
   * Slack plugin manager
   *
   * @var \Drupal\slack\Core\SlackApi\SlackApiManager
   */
  protected $slackApiManager;

  public function __construct(EntityTypeManager $entityTypeManager, Messenger $messenger, SlackApiManager $slackApiManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
    $this->slackApiManager = $slackApiManager;
  }

  public function getMethodsForm($form = [], $json_array = []) {
    $slack_app = $json_array['app'];
    $api_plugin = $json_array['plugin'];
    $method = $json_array['method'];

    if (empty($this->entityTypeManager->getStorage('slack_app')
      ->loadMultiple())) {
      $form['slack_test_api'] = [
        '#markup' => '<h2>App list is empty</h2>',
      ];
    }
    else {
      $form['slack_app'] = [
        '#type' => 'entity_autocomplete',
        '#title' => 'Slack App',
        '#target_type' => 'slack_app',
        '#tags' => TRUE,
        '#default' => $slack_app,
        '#selection_handler' => 'default',
        '#selection_settings' => [
        ]
      ];

      $plugin_list = $this->slackApiManager->getPluginsList();
      $form['slack_plugin'] = [
        '#type' => 'select',
        '#title' => t('Select plugin'),
        '#options' => $plugin_list,
        '#default' => $api_plugin,
      ];

      if (!empty($api_plugin)) {
        $form['slack_methods'] = [
          '#type' => 'select',
          '#title' => t('Select granted methods'),
          '#options' => [
          ],
        ];

        $plugin = $this->slackApiManager->createInstance($api_plugin);
        $methods = $plugin->getAvailableEndpoints();
        foreach ($methods as $method_key => $method_args) {
          if (!empty($method_args['title'])) {
            $form['slack_methods']['#options'][$method_key] = $method_args['title'];
          }
        }
        if (!is_array($method)) {
          $method = $methods[$method];
        }
      }

      if (!empty($method)) {
        $form['slack_methods']['#default_value'] = array_key_first($method);

        $form['slack_method_fieldset'] = [
          "#type" => 'fieldset',
          '#title' => t('Arguments')
        ];

        $form['slack_method_fieldset']['description'] = [
          '#markup' => '<p>' . $method['description'] . '</p>',
        ];

        foreach ($method['arguments'] as $key => $specs) {
          $form['slack_method_fieldset']['slack_method_' . $key] = [
            '#type' => $specs['type'],
            '#title' => $specs['title'],
            '#description' => $specs['description'],
            '#required' => $specs['required'],
            "#default_value" => $specs['value']
          ];
          if (!empty($specs['states'])) {
            $form['slack_method_fieldset']['slack_method_' . $key]['#states'] = $specs['states'];
          }
        }
      }
    }
    return $form;
  }

}
