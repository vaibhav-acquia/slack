<?php

/**
 * @file
 * Slack notifier.
 */

class MessageNotifierSlack extends MessageNotifierBase {

  public function deliver(array $output = array()) {

    if (!$webhook_url = slack_get_default_webhook_url()){
      drupal_set_message('Message cannot be sent if Webhook URL is not configured in Slack module settings.', 'error');
    }

    return slack_send_message($webhook_url, strip_tags($output['message_notify_slack_body']));
  }
}
