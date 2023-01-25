<?php

namespace Drupal\slack_userapi\Core\UserApi;

use Drupal\slack\Core\SlackApi\SlackApiBase;

/**
 * Base class for Chat Api.
 */
abstract class UserApiBase extends SlackApiBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints() {
    $json = file_get_contents(__DIR__. '/endpoints.json');
    return $endpoints = json_decode($json, true);
  }

}
