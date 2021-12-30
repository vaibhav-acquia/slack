<?php

namespace Drupal\slack\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Slack sender entities.
 *
 * @ingroup slack
 */
interface SlackSenderInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Slack sender name.
   *
   * @return string
   *   Name of the Slack sender.
   */
  public function getName();

  /**
   * Sets the Slack sender name.
   *
   * @param string $name
   *   The Slack sender name.
   *
   * @return \Drupal\slack\Entity\SlackSenderInterface
   *   The called Slack sender entity.
   */
  public function setName($name);

  /**
   * Gets the Slack sender creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Slack sender.
   */
  public function getCreatedTime();

  /**
   * Sets the Slack sender creation timestamp.
   *
   * @param int $timestamp
   *   The Slack sender creation timestamp.
   *
   * @return \Drupal\slack\Entity\SlackSenderInterface
   *   The called Slack sender entity.
   */
  public function setCreatedTime($timestamp);

}
