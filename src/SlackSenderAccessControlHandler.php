<?php

namespace Drupal\slack;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Slack sender entity.
 *
 * @see \Drupal\slack\Entity\SlackSender.
 */
class SlackSenderAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\slack\Entity\SlackSenderInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished slack sender entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published slack sender entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit slack sender entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete slack sender entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add slack sender entities');
  }


}
