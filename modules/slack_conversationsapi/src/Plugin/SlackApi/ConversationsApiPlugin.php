<?php

namespace Drupal\slack_conversationsapi\Plugin\SlackApi;

use Drupal\slack_conversationsapi\Core\ConversationsApi\ConversationsApiBase;

/**
 * Provides ChatApi.
 *
 * @SlackApi(
 *  id = "slack_conversationapi",
 *  label = @Translation("Slack Conversation API"),
 *  isApplicableForRules = true,
 * )
 */
class ConversationsApiPlugin extends ConversationsApiBase {

}
