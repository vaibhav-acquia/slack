<?php

namespace Drupal\slack;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Slack App entity.
 *
 * @see \Drupal\slack\Entity\SlackApp.
 */
class SlackAppAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\slack\Entity\SlackAppInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished slack app entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published slack app entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit slack app entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete slack app entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add slack app entities');
  }

}
