<?php

namespace Drupal\slack\Core\SlackApi;

use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\CategorizingPluginManagerTrait;
use Drupal\Core\Plugin\Context\ContextAwarePluginManagerTrait;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\slack\Core\Annotation\SlackApi;

/**
 * Provides an Chat Api plugin manager for the Slack API.
 *
 * @see plugin_api
 */
class SlackApiManager extends DefaultPluginManager implements SlackApiManagerInterface {

  use CategorizingPluginManagerTrait;
  use ContextAwarePluginManagerTrait;

  /**
   * Constructs a new class instance.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/SlackApi', $namespaces, $module_handler, SlackApiBaseInterface::class, SlackApi::class);
    $this->alterInfo('slack_api_info');
    $this->setCacheBackend($cache_backend, 'slack_api_info');
  }
  /**
   * Retrieves a list of available ChatApi plugins.
   *
   * @return string[]
   *   An associative array mapping the IDs of all available plugins to
   *   their labels.
   */
  public function getPluginsList() {
    $options = [];
    foreach ($this->getDefinitions() as $plugin_id => $plugin_definition) {
      $options[$plugin_id] = Html::escape($plugin_definition['label']);
    }
    return $options;
  }

  /**
   * Retrieves a list of ChatApi plugins, that are applicable for Rules.
   *
   * @return array
   */
  public function getPluginsApplicableForRules() {
    $options = [];
    foreach ($this->getDefinitions() as $plugin_id => $plugin_definition) {
      if ($plugin_definition['isApplicableForRules']) {
        $options[$plugin_id] = Html::escape($plugin_definition['label']);
      }
    }
    return $options;
  }

  /**
   * Retrieves an list of available EventsApi plugins by given properties.
   *
   * @param string $condition
   *   The property to filter on.
   * @param bool $value
   *   Value.
   *
   * @return string[]
   *   An array of the IDs of all available plugins.
   */
  public function getListByProperties($condition = NULL, $value = NULL) {
    $options = [];
    foreach ($this->getDefinitions() as $plugin_id => $plugin_definition) {
      if (empty($condition) || (isset($plugin_definition[$condition]) && $plugin_definition[$condition] === $value)) {
        $options[] = $plugin_id;
      }
    }
    return $options;
  }
}
