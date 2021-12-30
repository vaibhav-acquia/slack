<?php

/**
 * @file
 * Contains slack_request_content.page.inc.
 *
 * Page callback for Slack request content entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Slack request content templates.
 *
 * Default template: slack_request_content.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_slack_request_content(array &$variables) {
  // Fetch SlackRequestContent Entity Object.
  $slack_request_content = $variables['elements']['#slack_request_content'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
