<?php

namespace Drupal\slack;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Slack request content entities.
 *
 * @ingroup slack
 */
class SlackRequestContentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Slack request content ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\slack\Entity\SlackRequestContent $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.slack_request_content.edit_form',
      ['slack_request_content' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
