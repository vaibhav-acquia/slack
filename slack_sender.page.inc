<?php

/**
 * @file
 * Contains slack_sender.page.inc.
 *
 * Page callback for Slack sender entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Slack sender templates.
 *
 * Default template: slack_sender.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_slack_sender(array &$variables) {
  // Fetch SlackSender Entity Object.
  $slack_sender = $variables['elements']['#slack_sender'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
