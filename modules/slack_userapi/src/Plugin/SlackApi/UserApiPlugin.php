<?php

namespace Drupal\slack_userapi\Plugin\SlackApi;

use Drupal\slack_userapi\Core\UserApi\UserApiBase;
use Drupal\slack\Core\SlackApi;

/**
 * Provides UserApi.
 *
 * @SlackApi(
 *  id = "slack_userapi",
 *  label = @Translation("Slack User API"),
 *  isApplicableForRules = true,
 * )
 */
class UserApiPlugin extends UserApiBase {

  /**
   * @param $app_id
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function initMethods($app_id) {
    $token = $this->getAccessToken($app_id);
    $endpoints = $this->getAvailableEndpoints();

    return new SlackApi($token, $endpoints);
  }


  /**
   * @param string $app_id
   * @return mixed
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getAccessToken($app_id) {
    $slack_app_entity = $this->entityTypeManager->getStorage('slack_app')->load($app_id);
    return $slack_app_entity->getOAuthToken();
  }

}
