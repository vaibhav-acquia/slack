<?php

namespace Drupal\slack_rules\Plugin\RulesAction;

use Drupal\rules\Core\RulesActionBase;
use Drupal\slack\Service\SlackSendRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides a 'Slack send message' action.
 *
 * @RulesAction(
 *   id = "rules_slack_send_message",
 *   label = @Translation("Send message to Slack"),
 *   category = @Translation("Slack"),
 *   context_definitions = {
 *     "message" = @ContextDefinition("string",
 *       label = @Translation("Message"),
 *       description = @Translation("Specify the message, which should be sent to Slack."),
 *     ),
 *     "channel" = @ContextDefinition("string",
 *       label = @Translation("Channel"),
 *       description = @Translation("Specify the channel."),
 *       default_value = NULL,
 *       required = FALSE,
 *     ),
 *     "username" = @ContextDefinition("string",
 *       label = @Translation("User name"),
 *       description = @Translation("Specify the user name."),
 *       default_value = NULL,
 *       required = FALSE,
 *     ),
 *   }
 * )
 */
class SlackRulesSendMessage extends RulesActionBase implements ContainerFactoryPluginInterface {

  /**
   * Slack service.
   *
   * @var \Drupal\slack\Service\SlackSendRequest
   */
  protected $slackService;

  /**
   * Constructs a SlackRulesSendMessage object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\slack\Service\SlackSendRequest $slack_service
   *   The Slack manager service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, SlackSendRequest $slack_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->slackService = $slack_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('slack.slack_send_message')
    );
  }

  /**
   * Send message to slack.
   *
   * @param string $message
   *   The message to be sent.
   * @param string $channel
   *   The slack channel.
   * @param string $username
   *   The slack username.
   */
  protected function doExecute($message, $channel = '', $username = '') {
    $this->slackService->sendMessage($message, $channel, $username);
  }

}
