<?php

namespace Drupal\slack\Core\SlackApi;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Implements ChatApi.
 */
interface SlackApiPluginInterface extends PluginInspectionInterface {

  /**
   * Retrieve all endpoints available in this plugin.
   *
   * @return array
   *   Associative array representation of the JSON endpoint.
   */
  public function getAvailableEndpoints(): array;

}
