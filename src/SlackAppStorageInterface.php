<?php

namespace Drupal\slack;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\slack\Entity\SlackAppInterface;

/**
 * Defines the storage handler class for Slack App entities.
 *
 * This extends the base storage class, adding required special handling for
 * Slack App entities.
 *
 * @ingroup slack
 */
interface SlackAppStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Slack App revision IDs for a specific Slack App.
   *
   * @param \Drupal\slack\Entity\SlackAppInterface $entity
   *   The Slack App entity.
   *
   * @return int[]
   *   Slack App revision IDs (in ascending order).
   */
  public function revisionIds(SlackAppInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Slack App author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Slack App revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\slack\Entity\SlackAppInterface $entity
   *   The Slack App entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SlackAppInterface $entity);

  /**
   * Unsets the language for all Slack App with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
