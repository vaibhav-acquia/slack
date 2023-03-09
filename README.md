# Slack

The Slack module brings all your communication together in one place.
It’s real-time messaging, archiving and search for modern teams, and it has cool
system integrations features.

This module allows you to send messages from a Drupal website to Slack.
It has Rules module integration. You can also use our module API in your modules.

For a full description of the module, visit the
[project page](https://www.drupal.org/project/slack).

Submit bug reports and feature suggestions, or track changes in the
[issue queue](https://www.drupal.org/project/issues/slack).


## Table of contents

- Requirements
- Recommended Modules
- Installation
- Configuration
- Maintainers


## Requirements

This module requires the following outside of Drupal core:

- Slack account - `https://your-team-domain.slack.com`

A Slack app or webhook integration is required. Although webhooks considered
deprecated by Slack they are still can be used.
For app:
1. Navigate to https://api.slack.com/apps/ and press `Create new app`.
2. Select appropriate variant to create it, fill name and workspace.
3. After submit, you will be redirected to app page, select
   `OAuth & Permissions` in menu on left.
4. Setup scopes.
5. Return back to `Basic Information` page and click `Install to workspace`.

For webhooks:
1. Navigate to
   `https://your-team-domain.slack.com/apps/manage/custom-integrations`.
2. Select `Incoming Webhooks` and `Add Configuration`.
   Choose a channel (or create a new one) to integrate and select
   `Add Incoming Webhooks integration`.
3. Upon saving, the user will be redirected to a page with the Webhook URL.

For more information:
- https://api.slack.com/scopes
- https://api.slack.com/custom-integrations
- https://api.slack.com/incoming-webhooks


## Recommended Modules

To enable the Rules-based Slack integration:

- [Rules](https://www.drupal.org/project/rules)

Useful links (about the Rules module):

- https://www.drupal.org/documentation/modules/rules
- https://fago.gitbooks.io/rules-docs/content/


## Installation

Install as you would normally install a contributed Drupal module.
For further information, see
[Installing Drupal Modules](https://www.drupal.org/docs/extending-drupal/installing-drupal-modules).


## Configuration

**App configuration:**

1. Navigate to `Administration > Extend` and enable the module and any API
   submodules you intend to use.
2. Navigate to `Administration > Configuration > Web Services > Slack >
   Slack App list` and click `Add Slack App`.
3. Give it meaningful name and fill fields with information from
   `Basic Information` and `OAuth & Permissions` pages in Slack App.
4. Save app.
5. You can also add any fields you want on `Administration > Configuration >
   Web Services > Slack > Slack App Entity settings` in case you want to
   store extra information.
6. To test the messaging system, navigate to `Administration > Configuration >
   Web Services > Slack > Send test request`. Enter an app name, select
   API and method you want to test, fill required fields that appeared
   below and press `Send message`. The message should be sent using
   credentials and from selected app.

**Webhooks configuration:**

1. Navigate to `Administration > Extend` and enable the module.
2. Navigate to `Administration > Configuration > Web Services > Slack >
   Configuration` to configure the Slack module.
3. Enter the Webhook URL that was obtained from
   `https://your-team-domain.slack.com/apps/manage/custom-integrations`.
4. Enter the channel name with the # symbol (or @username for a private
   message or a private group name).
5. Enter the Default username that you would like to name your Slack bot.
6. Select the type of image: Emoji, Image, or None (Use default
   integration settings).
7. Choose if message should be sent with attachment styling.
8. Save configuration.
9. To test the messaging system, navigate to
   `Administration > Configuration > Web Services > Slack > Send a test message`.
   Enter a message and select `Send message`.
   The message should be sent to the selected Slack channel or user.


## Maintainers

- adci_contributor - [adci_contributor](https://www.drupal.org/u/adci_contributor)
- Evgeny Leonov - [hxdef](https://www.drupal.org/u/hxdef)

**Supporting organization:**

- [ADCI Solutions](https://www.drupal.org/adci-solutions)
