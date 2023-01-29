<?php

namespace Drupal\slack_chatapi\Plugin\SlackApi;

use Drupal\slack_chatapi\Core\ChatApi\ChatApiPluginBase;

/**
 * Provides ChatApi.
 *
 * @SlackApi(
 *  id = "slack_chatapi",
 *  label = @Translation("Slack Chat API"),
 *  isApplicableForRules = true,
 * )
 */
class ChatApiPlugin extends ChatApiPluginBase {

}
