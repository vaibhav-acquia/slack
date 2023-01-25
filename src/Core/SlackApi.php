<?php

namespace Drupal\slack\Core;

use GuzzleHttp\Client;

/**
 * Class SlackApi
 *
 * @package wrapi\slack_api
 */
class SlackApi {

  public const baseUrl = 'https://slack.com/api/';

  protected $endpoints = [];

  protected $opts = [];

  /**
   * SlackApi constructor.
   *
   * @param $tokens
   * @param array $endpoints
   */
  public function __construct($token, array $endpoints) {
    $this->opts = [
      "headers" => [
        'Authorization' => 'Bearer ' . $token,
      ],
      "token" => $token,
    ];
    $this->endpoints = $endpoints;
  }

  public function getMethods() {
    return $this->endpoints;
  }

  public function getHeaders() {
    return $this->opts;
  }

  public function sendRequest(string $method, array $params, $content_type = 'default') {
    $params['token'] = $this->opts['token'];
    $this->opts['headers']['Content-type'] = $this->endpoints[$method]['content_type'][$content_type];
//    if (str_contains($content_type , 'json')) {
//      $this->opts['json'] = $params;
//    } else {
      $this->opts['query'] = $params;
//    }
    $client = new Client($this->opts);
    $response = $client->request($this->endpoints[$method]['method'], 'https://slack.com/api/' . $method);
    $body = json_decode($response->getBody()->getContents(), TRUE);
    return $body;
  }

}
