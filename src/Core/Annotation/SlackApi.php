<?php

namespace Drupal\slack\Core\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Web API item annotation object.
 *
 * @see \Drupal\slack_api\Core\SlackApi\SlackApiManager
 * @see plugin_api
 *
 * @Annotation
 */
class SlackApi extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * Indicates if plugin is applicable for Rules or not.
   *
   * @var bool
   */
  public $isApplicableForRules;
}
