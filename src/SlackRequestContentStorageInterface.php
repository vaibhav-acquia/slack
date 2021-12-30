<?php

namespace Drupal\slack;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\slack\Entity\SlackRequestContentInterface;

/**
 * Defines the storage handler class for Slack request content entities.
 *
 * This extends the base storage class, adding required special handling for
 * Slack request content entities.
 *
 * @ingroup slack
 */
interface SlackRequestContentStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Slack request content revision IDs for a specific Slack request content.
   *
   * @param \Drupal\slack\Entity\SlackRequestContentInterface $entity
   *   The Slack request content entity.
   *
   * @return int[]
   *   Slack request content revision IDs (in ascending order).
   */
  public function revisionIds(SlackRequestContentInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Slack request content author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Slack request content revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\slack\Entity\SlackRequestContentInterface $entity
   *   The Slack request content entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SlackRequestContentInterface $entity);

  /**
   * Unsets the language for all Slack request content with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
