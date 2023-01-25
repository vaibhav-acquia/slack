<?php

namespace Drupal\slack\Core\SlackApi;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Implements ChatApi.
 */
interface SlackApiBaseInterface extends PluginInspectionInterface {

  /**
   * Retrieve all endpoints available in this plugin.
   *
   * @return mixed
   */
  public function getAvailableEndpoints();

}
