<?php

namespace Drupal\slack;

use Drupal\slack\Entity\SlackApp;

/**
 * Implements interface SlackApiIntegrationInterface.
 */
interface SlackApiIntegrationInterface {

  const JSON_MEDIA_TYPE = 'application/json';

  const FORM_URL_ENCODED = 'application/x-www-form-urlencoded';

  const GET = 'GET';

  const POST = 'POST';

  const PUT = 'PUT';

  /**
   * Send API request.
   *
   * @param string $endpoint
   *   Endpoint.
   * @param array $options
   *   Array of request options.
   * @param string $type
   *   Request type.
   * @param string $media_type
   *   Media type in request headers.
   *
   * @return array|bool
   *   Response array or false.
   *
   * @throws \Drupal\Core\TempStore\TempStoreException
   */
  public function sendApiRequest($endpoint, array $options, $type = self::POST, $media_type = self::FORM_URL_ENCODED);

  /**
   * Helper function to send request to the external resource.
   *
   * @param string $authorization
   *   Authorization header.
   * @param string $media
   *   Content type in request headers.
   * @param string $endpoint
   *   Endpoint.
   * @param array|string $data
   *   Data send in request.
   * @param string $type
   *   Request type.
   *
   * @return array|bool
   *   Response array.
   */
  public function sendHttpRequest($media, $endpoint, $data, $type = self::POST, $authorization = NULL);

  /**
   * Set slack app entity.
   *
   * @param \Drupal\slack\Entity\SlackApp $slack
   *   The SlackApp entity.
   *
   * @return mixed
   */
  public function setSlackApp(SlackApp $slack);

}
