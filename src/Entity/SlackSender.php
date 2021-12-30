<?php

namespace Drupal\slack\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Slack sender entity.
 *
 * @ingroup slack
 *
 * @ContentEntityType(
 *   id = "slack_sender",
 *   label = @Translation("Slack sender"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\slack\SlackSenderListBuilder",
 *     "views_data" = "Drupal\slack\Entity\SlackSenderViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\slack\Form\SlackSenderForm",
 *       "add" = "Drupal\slack\Form\SlackSenderForm",
 *       "edit" = "Drupal\slack\Form\SlackSenderForm",
 *       "delete" = "Drupal\slack\Form\SlackSenderDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\slack\SlackSenderHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\slack\SlackSenderAccessControlHandler",
 *   },
 *   base_table = "slack_sender",
 *   translatable = FALSE,
 *   admin_permission = "administer slack sender entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/slack_sender/{slack_sender}",
 *     "add-form" = "/admin/structure/slack_sender/add",
 *     "edit-form" = "/admin/structure/slack_sender/{slack_sender}/edit",
 *     "delete-form" = "/admin/structure/slack_sender/{slack_sender}/delete",
 *     "collection" = "/admin/structure/slack_sender",
 *   },
 *   field_ui_base_route = "slack_sender.settings"
 * )
 */
class SlackSender extends ContentEntityBase implements SlackSenderInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Slack sender entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status']->setDescription(t('A boolean indicating whether the Slack sender is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
