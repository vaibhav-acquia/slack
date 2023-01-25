<?php

/**
 * @file
 * Contains slack_app.page.inc.
 *
 * Page callback for Slack App entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Slack App templates.
 *
 * Default template: slack_app.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_slack_app(array &$variables) {
  // Fetch SlackApp Entity Object.
  $slack_app = $variables['elements']['#slack_app'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
