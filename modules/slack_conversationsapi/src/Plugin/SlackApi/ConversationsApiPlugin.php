<?php

namespace Drupal\slack_conversationsapi\Plugin\SlackApi;

use Drupal\slack_conversationsapi\Core\ConversationsApi\ConversationsApiPluginBase;

/**
 * Provides ChatApi.
 *
 * @SlackApi(
 *  id = "slack_conversationsapi",
 *  label = @Translation("Slack Conversations API"),
 *  isApplicableForRules = true,
 * )
 */
class ConversationsApiPlugin extends ConversationsApiPluginBase {

}
