<?php

namespace Drupal\slack_webapi\Core\WebApi;

use Drupal\slack\Core\SlackApi\SlackApiBase;

/**
 * Base class for Web Api.
 */
abstract class WebApiBase extends SlackApiBase {

  /**
   * {@inheritdoc}
   */
  public function getAvailableEndpoints() {
    $json = file_get_contents(__DIR__. '/endpoints.json');
    return $endpoints = json_decode($json, true);
  }

}
