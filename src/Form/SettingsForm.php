<?php

namespace Drupal\slack\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\key\KeyRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Builds the configuration form for slack integration.
 *
 * @package Drupal\slack\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The module manager service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected ModuleHandlerInterface $moduleHandler;

  /**
   * The Key repository service.
   *
   * @var \Drupal\key\KeyRepositoryInterface
   */
  protected $keyRepository;

  /**
   * Constructs an AutologoutSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module manager service.
   * @param \Drupal\key\KeyRepositoryInterface|null $key_repository
   *   The token service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, KeyRepositoryInterface $key_repository = NULL) {
    parent::__construct($config_factory);
    $this->moduleHandler = $module_handler;
    $this->keyRepository = $key_repository;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('key.repository', ContainerInterface::NULL_ON_INVALID_REFERENCE),
    );
  }

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'slack_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['slack.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('slack.settings');

    $form['info'] = [
      '#type' => 'item',
      '#title' => $this->t('About webhook'),
      '#markup' => $this->t('You should provide a "Webhook URL" or a "Key Machine name" (requires key module) in order to send messages.'),
    ];

    $form['slack_webhook_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Webhook URL'),
      '#description' => $this->t('Enter your Webhook URL from an Incoming WebHooks integration. It looks like https://hooks.slack.com/services/XXXXXXXXX/YYYYYYYYY/ZZZZZZZZZZZZZZZZZZZZZZZZ'),
      '#default_value' => $config->get('slack_webhook_url'),
    ];
    if ($this->moduleHandler->moduleExists('key')) {
      $options = [];
      $options[''] = "Select a key";
      $keys = $this->keyRepository->getKeys();
      foreach ($keys as $key => $value) {
        $options[$key] = $key;
      }
      $form['slack_webhook_key'] = [
        '#type' => 'select',
        '#title' => $this->t('Key Machine name'),
        '#options' => $options,
        '#default_value' => $config->get('slack_webhook_key'),
      ];
    }

    $form['slack_channel'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default channel'),
      '#description' => $this->t('Enter your channel name with # symbol, for example #general (or @username for a private message or a private group name).'),
      '#default_value' => $config->get('slack_channel'),
    ];
    $form['slack_username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Default username'),
      '#description' => $this->t('What would you like to name your Slack bot?'),
      '#default_value' => $config->get('slack_username'),
    ];
    $form['slack_icon_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Type of image'),
      '#options' => [
        'emoji' => $this->t('Emoji'),
        'image' => $this->t('Image'),
        'none' => $this->t('None (Use default integration settings)'),
      ],
      '#default_value' => $config->get('slack_icon_type'),
    ];
    $form['slack_icon_emoji'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Emoji code'),
      '#default_value' => $config->get('slack_icon_emoji'),
      '#description' => $this->t('What emoji would you use for your SlackBot?'),
      '#states' => [
        'visible' => [
          ':input[name="slack_icon_type"]' => [
            'value' => 'emoji',
          ],
        ],
      ],
    ];
    $form['slack_icon_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Image URL'),
      '#default_value' => $config->get('slack_icon_url'),
      '#description' => $this->t('What icon would you use for your SlackBot?'),
      '#states' => [
        'visible' => [
          ':input[name="slack_icon_type"]' => [
            'value' => 'image',
          ],
        ],
      ],
    ];
    $form['slack_link_names'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Link names?'),
      '#description' => $this->t('Should names be linked in messages? See https://api.slack.com/methods/chat.postMessage#arg_link_names'),
      '#default_value' => $config->get('slack_link_names'),
    ];
    if (empty($config->get('slack_webhook_url'))) {
      $this->messenger()
        ->addWarning($this->t('Slack sending message page will be available after you fill "Webhook URL" field'));
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('slack.settings');
    $config
      ->set('slack_webhook_url', trim($form_state->getValue('slack_webhook_url')))
      ->set('slack_webhook_key', trim($form_state->getValue('slack_webhook_key')))
      ->set('slack_channel', $form_state->getValue('slack_channel'))
      ->set('slack_username', $form_state->getValue('slack_username'))
      ->set('slack_icon_type', $form_state->getValue('slack_icon_type'))
      ->set('slack_icon_emoji', $form_state->getValue('slack_icon_emoji'))
      ->set('slack_icon_url', $form_state->getValue('slack_icon_url'))
      ->set('slack_link_names', $form_state->getValue('slack_link_names'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
