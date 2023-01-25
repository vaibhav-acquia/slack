<?php

namespace Drupal\slack\Service;

use Drupal;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Messenger\Messenger;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\slack\SlackApiIntegration;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class SlackSendMessage.
 */
class SlackSendRequest extends SlackApiIntegration {

  use StringTranslationTrait;

  /**
   * Messenger service
   *
   * @var \Drupal\Core\Messenger\Messenger
   */
  protected $messenger;

  protected $config;

  /**
   * SlackSendMessage constructor.
   *
   * @param Client $client
   * @param LoggerChannelFactory $logger_factory
   * @param Messenger $messenger
   */
  public function __construct(Client $client, LoggerChannelFactory $logger_factory, Messenger $messenger) {
    parent::__construct($client, $logger_factory);
    $this->messenger = $messenger;
    $this->config = Drupal::config('slack.settings');
  }

  /**
   * Send message to the Slack with more options.
   *
   * @param $webhook_url
   * @param array $options
   * @param string $type
   * @param string $media_type
   *
   * @return array|bool Can contain:
   *   Can contain:
   *                          success      fail          fail
   *     - data:                ok         No hooks      Invalid channel
   *   specified
   *     - status message:      OK         Not found     Server Error
   *     - code:                200        404           500
   *     - error:               -          Not found     Server Error
   */
  public function sendMessageApiRequest($webhook_url, string $sending_data, string $type = self::POST, string $media_type = self::JSON_MEDIA_TYPE) {
    $logger = $this->loggerFactory->get('slack');
    try {
      $response = $this->sendHttpRequest(self::FORM_URL_ENCODED, $webhook_url, $sending_data, $type, NULL, FALSE);
      $logger->info('Message was successfully sent!');
      return $response;
    }
    catch (\GuzzleHttp\Exception\ServerException $e) {
      $logger->error('Server error! It may appear if you try to use unexisting chatroom.');
      watchdog_exception('slack', $e);
      return FALSE;
    }
    catch (\GuzzleHttp\Exception\ConnectException $e) {
      $logger->error('Connection error! Something wrong with your connection. Message was\'nt sent.');
      watchdog_exception('slack', $e);
      return FALSE;
    }
    catch (\GuzzleHttp\Exception\RequestException $e) {
      $logger->error('Request error! It may appear if you entered the invalid Webhook value.');
      watchdog_exception('slack', $e);
      return FALSE;
    }
  }

  /**
   * Send message to the Slack.
   *
   * @param string $message
   *   The message sent to the channel.
   * @param string $channel
   *   The channel in the Slack service to send messages.
   * @param string $username
   *   The bot name displayed in the channel.
   *
   * @return bool|object
   *   Slack response.
   *
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function sendMessage(string $webhook_url, string $message) {
    $this->loggerFactory->get('slack')
      ->info('Sending message "@message"', [
        '@message' => $message,
      ]);

    $message_options['text'] = $this->processMessage($message);
    $sending_data = 'payload=' . urlencode(json_encode($message_options));

    return $this->sendMessageApiRequest($webhook_url, $sending_data);
  }

  /**
   * Replaces links with slack friendly tags. Strips all other html.
   *
   * @param string $message
   *   The message sent to the channel.
   *
   * @return string
   *   Replaces links with slack friendly tags. Strips all other html.
   */
  private function processMessage(string $message) {
    $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
    if (preg_match_all("/$regexp/siU", $message, $matches, PREG_SET_ORDER)) {
      $i = 1;
      foreach ($matches as $match) {
        $new_link = "<$match[2] | $match[3]>";
        $links['link-' . $i] = $new_link;
        $message = str_replace($match[0], 'link-' . $i, $message);
        $i++;
        $message = strip_tags($message);
        foreach ($links as $id => $link) {
          $message = str_replace($id, $link, $message);
        }
      }
    }
    return $message;
  }

}
