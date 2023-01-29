<?php

namespace Drupal\slack_conversationsapi\Core\ConversationsApi;

use Drupal\slack\Core\SlackApi\SlackApiBase;

/**
 * Base class for Chat Api.
 */
abstract class ConversationsApiBase extends SlackApiBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints() {
    $json = file_get_contents(__DIR__ . '/endpoints.json');
    return $endpoints = json_decode($json, TRUE);
  }

}
