<?php

namespace Drupal\slack;

use Drupal;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\slack\Entity\SlackApp;
use GuzzleHttp\Client;

/**
 * Class SlackApiIntegrationBase.
 */
class SlackApiIntegrationBase implements SlackApiIntegrationInterface {

  /**
   * The base url.
   */
  public const baseUrl = 'https://slack.com/api/';

  /**
   * GuzzleHttp\Client definition.
   *
   * @var \GuzzleHttp\Client
   */
  protected $client;

  /**
   * Drupal logger.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * Slack app.
   *
   * @var \Drupal\slack\Entity\SlackApp
   */
  protected $slack_app;

  /**
   * Constructs a new SlackApiIntegrationBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Config factory.
   * @param \GuzzleHttp\Client $client
   *   Http Client.
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempstore
   *   Private tempstore factory.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $logger_factory
   *   Drupal logger service.
   */
  public function __construct(Client $client, LoggerChannelFactory $logger_factory) {
    $this->client = $client;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function sendApiRequest($endpoint, array $options, $type = self::POST, $media_type = self::JSON_MEDIA_TYPE) {

  }

  /**
   * {@inheritdoc}
   */
  public function sendHttpRequest($media, $endpoint, $data, $type = self::POST, $authorization = NULL) {

  }

  /**
   * {@inheritdoc}
   */
  public function setSlackApp($slack) {
    $this->slack_app = $slack;
  }

}
