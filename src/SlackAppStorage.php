<?php

namespace Drupal\slack;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class SlackAppStorage extends SqlContentEntityStorage implements SlackAppStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(SlackAppInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {slack_app_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {slack_app_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(SlackAppInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {slack_app_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('slack_app_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
