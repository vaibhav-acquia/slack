<?php

namespace Drupal\slack_webapi\Core\WebApi;

use Drupal\slack\Core\SlackApi\SlackApiPluginBase;

/**
 * Base class for WebApi plugins.
 */
abstract class WebApiPluginBase extends SlackApiPluginBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints(): array {
    $json = file_get_contents(__DIR__ . '/endpoints.json');
    return $endpoints = json_decode($json, TRUE);
  }

}
