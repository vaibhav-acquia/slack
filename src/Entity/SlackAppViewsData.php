<?php

namespace Drupal\slack\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Slack App entities.
 */
class SlackAppViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
