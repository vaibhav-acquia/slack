<?php

namespace Drupal\slack\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Slack request content type entity.
 *
 * @ConfigEntityType(
 *   id = "slack_request_content_type",
 *   label = @Translation("Slack request content type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\slack\SlackRequestContentTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\slack\Form\SlackRequestContentTypeForm",
 *       "edit" = "Drupal\slack\Form\SlackRequestContentTypeForm",
 *       "delete" = "Drupal\slack\Form\SlackRequestContentTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\slack\SlackRequestContentTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "slack_request_content_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "slack_request_content",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/slack_request_content_type/{slack_request_content_type}",
 *     "add-form" = "/admin/structure/slack_request_content_type/add",
 *     "edit-form" = "/admin/structure/slack_request_content_type/{slack_request_content_type}/edit",
 *     "delete-form" = "/admin/structure/slack_request_content_type/{slack_request_content_type}/delete",
 *     "collection" = "/admin/structure/slack_request_content_type"
 *   },
 *   config_export = {
 *     "id",
 *     "label"
 *   }
 * )
 */
class SlackRequestContentType extends ConfigEntityBundleBase implements SlackRequestContentTypeInterface {

  /**
   * The Slack request content type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Slack request content type label.
   *
   * @var string
   */
  protected $label;

}
