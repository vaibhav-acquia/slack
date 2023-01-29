<?php

namespace Drupal\slack_userapi\Core\UserApi;

use Drupal\slack\Core\SlackApi\SlackApiPluginBase;

/**
 * Base class for UserApi plugins.
 */
abstract class UserApiPluginBase extends SlackApiPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints(): array {
    $json = file_get_contents(__DIR__ . '/endpoints.json');
    return $endpoints = json_decode($json, TRUE);
  }

}
