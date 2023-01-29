<?php

namespace Drupal\slack_chatapi\Core\ChatApi;

use Drupal\slack\Core\SlackApi\SlackApiPluginBase;

/**
 * Base class for ChatApi plugins.
 */
abstract class ChatApiPluginBase extends SlackApiPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints(): array {
    $json = file_get_contents(__DIR__ . '/endpoints.json');
    return $endpoints = json_decode($json, TRUE);
  }

}
