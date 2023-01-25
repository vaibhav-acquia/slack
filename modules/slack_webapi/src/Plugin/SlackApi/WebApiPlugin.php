<?php

namespace Drupal\slack_webapi\Plugin\SlackApi;

use Drupal\slack_webapi\Core\WebApi\WebApiBase;
use Drupal\slack\Core\SlackApi;

/**
 * Provides WebApi.
 *
 * @SlackApi(
 *  id = "slack_webapi",
 *  label = @Translation("Slack Web API"),
 * )
 */
class WebApiPlugin extends WebApiBase {

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

  public function getBotUserAccessToken($app_id) {
    $slack_app_entity = $this->entityTypeManager->getStorage('slack_app')->load($app_id);
    return $slack_app_entity->getBotUserOAuthToken();
  }

  public function getAppAccessToken($app_id) {
    $slack_app_entity = $this->entityTypeManager->getStorage('slack_app')->load($app_id);
    return $slack_app_entity->getAuthorisationToken();
  }

}
