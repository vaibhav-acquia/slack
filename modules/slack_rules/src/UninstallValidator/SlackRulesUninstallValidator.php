<?php

namespace Drupal\slack_rules\UninstallValidator;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\Link;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\rules\Core\RulesActionManagerInterface;

class SlackRulesUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * The rules action plugin manager.
   *
   * @var \Drupal\rules\Core\RulesActionManagerInterface
   */
  protected $pluginManager;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new SlackRulesUninstallValidator object.
   *
   * @param RulesActionManagerInterface $plugin_manager
   *   Rules action plugin manager
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(RulesActionManagerInterface $plugin_manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->pluginManager = $plugin_manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    if ($module != 'slack_rules') {
      return '';
    }
    $plugin_id = 'rules_slack_send_message';
    $reasons = $plugins = [];
    /** @var \Drupal\rules\Core\RulesActionManagerInterface $plugin */
    foreach ($this->hasSlackRulesAction($plugin_id) as $rules_action_id => $rules_action) {
      $plugins[$rules_action_id] = [
        'name' => $rules_action->label(),
        'edit_url' => Link::createFromRoute($rules_action->label(), 'entity.rules_reaction_rule.edit_form', ['rules_reaction_rule' => $rules_action_id])
          ->getUrl()
          ->toString()
      ];
    }
    if (!empty($plugins)) {
      foreach ($plugins as $plugin) {
        $reasons[] = $this->t('The following rule still using "Send message to Slack" action: <a href ="@plugin_url">@plugin</a>', [
          '@plugin' => $plugin['name'],
          '@plugin_url'=> $plugin['edit_url']
        ]);
      }
    }

    return $reasons;
  }

  /**
   * Determines if there is any rules actions.
   *
   * @param string $plugin_id
   *   The rules action plugin ID to checking.
   *
   * @return array
   *   Whether there are rules actions for the given plugin ID.
   */
  protected function hasSlackRulesAction($plugin_id) {
    $reaction_rule_config = [];
    $values = [
      'expression' => [
        'actions' => [
          'actions' => [
            'action_id' => $plugin_id,
          ]
        ]
      ]
    ];
    try {
      $reaction_rule_storage = $this->entityTypeManager->getStorage('rules_reaction_rule');
      $reaction_rule_config = $reaction_rule_storage->loadByProperties($values);
    } catch (InvalidPluginDefinitionException $e) {
    } catch (PluginNotFoundException $e) {
    }

    return $reaction_rule_config;
  }

}
