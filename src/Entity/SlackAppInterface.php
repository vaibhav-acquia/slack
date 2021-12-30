<?php

namespace Drupal\slack\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Slack App entities.
 *
 * @ingroup slack
 */
interface SlackAppInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Slack App name.
   *
   * @return string
   *   Name of the Slack App.
   */
  public function getName();

  /**
   * Sets the Slack App name.
   *
   * @param string $name
   *   The Slack App name.
   *
   * @return \Drupal\slack\Entity\SlackAppInterface
   *   The called Slack App entity.
   */
  public function setName($name);

  /**
   * Gets the Slack App creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Slack App.
   */
  public function getCreatedTime();

  /**
   * Sets the Slack App creation timestamp.
   *
   * @param int $timestamp
   *   The Slack App creation timestamp.
   *
   * @return \Drupal\slack\Entity\SlackAppInterface
   *   The called Slack App entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Slack App revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Slack App revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\slack\Entity\SlackAppInterface
   *   The called Slack App entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Slack App revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Slack App revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\slack\Entity\SlackAppInterface
   *   The called Slack App entity.
   */
  public function setRevisionUserId($uid);

}
