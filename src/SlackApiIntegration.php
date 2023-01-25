<?php

namespace Drupal\slack;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SlackApiIntegrationBase.
 */
class SlackApiIntegration extends SlackApiIntegrationBase {

  /**
   * {@inheritdoc}
   */
  public function sendApiRequest($endpoint, array $options, $type = self::POST, $media_type = self::FORM_URL_ENCODED) {
    if (!empty($this->slack_app)) {
      $data = $options;
      $response = $this->sendHttpRequest($media_type, $endpoint, $data, $type, 'Bearer ' . $this->slack_app->getOAuthToken());

      if (!empty($response)) {
        $response = json_decode($response->getBody()->getContents(), TRUE);
      }
      return $response;
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function sendHttpRequest($media, $endpoint, $data, $type = self::POST, $authorization = NULL, $is_api = TRUE) {
    $options = [
      'headers' => [
        'Content-Type' => $media,
      ],
    ];

    if (!is_null($authorization)) {
      $options['headers']['Authorization'] = $authorization;
    }

    if ($is_api) {
      $url = self::baseUrl . $endpoint;
      $options['query'] = $data;
    }
    else {
      $url = $endpoint;
      $options['body'] = $data;
    }

    try {
      $response = $this->client->request($type, $url, $options);
      return $response;
    }
    catch (ClientException $e) {
      $response = $e->getResponse();
      return $response;
    }
    catch (GuzzleException $e) {
      $logger = $this->loggerFactory->get('slack');
      $logger->error($e->getMessage());
    }
    return FALSE;
  }

}
