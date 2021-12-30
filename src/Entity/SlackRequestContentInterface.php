<?php

namespace Drupal\slack\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Slack request content entities.
 *
 * @ingroup slack
 */
interface SlackRequestContentInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Slack request content name.
   *
   * @return string
   *   Name of the Slack request content.
   */
  public function getName();

  /**
   * Sets the Slack request content name.
   *
   * @param string $name
   *   The Slack request content name.
   *
   * @return \Drupal\slack\Entity\SlackRequestContentInterface
   *   The called Slack request content entity.
   */
  public function setName($name);

  /**
   * Gets the Slack request content creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Slack request content.
   */
  public function getCreatedTime();

  /**
   * Sets the Slack request content creation timestamp.
   *
   * @param int $timestamp
   *   The Slack request content creation timestamp.
   *
   * @return \Drupal\slack\Entity\SlackRequestContentInterface
   *   The called Slack request content entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Slack request content revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Slack request content revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\slack\Entity\SlackRequestContentInterface
   *   The called Slack request content entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Slack request content revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Slack request content revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\slack\Entity\SlackRequestContentInterface
   *   The called Slack request content entity.
   */
  public function setRevisionUserId($uid);

}
