<?php

namespace Drupal\Tests\slack\Functional\Form;

use Drupal\Core\Url;

/**
 * Tests the Slack settings form.
 *
 * @group slack
 */
class SettingFormTest extends SlackBrowserTestBase {

  /**
   * Tests for \Drupal\slack\Form\WebhooksForm.
   */
  public function testModuleFilterSettingsForm(): void {
    $assert = $this->assertSession();

    $this->drupalGet(Url::fromRoute('slack.admin_webhook'));
    $assert->statusCodeEquals(200);

    $assert->pageTextContains('Webhook URL');
    $assert->pageTextContains('Message');
    $assert->buttonExists('Send message');

    // @todo Add asserts.
  }

}
