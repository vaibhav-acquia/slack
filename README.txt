CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Recommended Modules
 * Installation
 * Configuration
 * Maintainers


INTRODUCTION
------------

The Slack module brings all your communication together in one place. It’s
real-time messaging, archiving and search for modern teams, and it has cool
system integrations features.

This module allows you to send messages from a Drupal website to Slack.
It has Rules module integration. Also you can use our module API in your
modules.

 * For a full description of the module visit:
   https://www.drupal.org/project/slack

 * To submit bug reports and feature suggestions, or to track changes visit:
   https://www.drupal.org/project/issues/slack


REQUIREMENTS
------------

This module requires the following outside of Drupal core:

 * Slack account - https://your-team-domain.slack.com

A webhook integration is required.
    1. Navigate to
       https://your-team-domain.slack.com/apps/manage/custom-integrations.
    2. Select "Incoming Webhooks" and "Add Configuration." Choose a channel
       (or create a new one) to integrate and select " Add Incoming Webhooks
       integration."
    3. Upon saving, the user will be redirected to a page with the Webhook URL.

For more information:
 * https://api.slack.com/custom-integrations
 * https://api.slack.com/incoming-webhooks


RECOMMENDED MODULES
-------------------

To enable the Rules-based Slack integration:

 * Rules - https://www.drupal.org/project/rules

Useful links (about the Rules module):

 * https://www.drupal.org/documentation/modules/rules
 * https://fago.gitbooks.io/rules-docs/content/


INSTALLATION
------------

 * Install the Slack module as you would normally install a contributed Drupal
   module. Visit https://www.drupal.org/node/895232 for further information.


CONFIGURATION
-------------

    1. Navigate to Administration > Modules and enable the module.
    2. Navigate to Administration > Configuration > Web Services > Slack >
       Configuration to configure the Slack module.
    3. Enter the Webhook URL that was obtained from
       https://your-team-domain.slack.com/apps/manage/custom-integrations.
    4. Enter the channel name with the # symbol (or @username for a private
       message or a private group name).
    5. Enter the Default user name that you would like to name your Slack bot.
    6. Select the type of image: Emoji, Image, or None (Use default
       integration settings).
    7. Choose if message should be sent with attachment styling.
    8. Save configuration.
    9. To test the messaging system, navigate to Administration > Configuration
       > Web Services > Slack > Send a Message. Enter a message and select "Send
       message." The message should be sent to the selected Slack channel or
       user.
    10.If you want to automatically delete files from slack workspace, you
       should provide token, which you can generate here:
       https://api.slack.com/custom-integrations/legacy-tokens#legacy-info
       Or you can get more secure oauth tokens here:
       - go to https://api.slack.com/apps (sign in into your slack account
         of course)
       - choose app that you will grant permission to file access
       - select OAuth & Permissions in sidebar
       - copy 'OAuth Access Token' into settings of slack module on your
         drupal site
       - scroll to 'Select Permission Scopes' and select files:read and
         files:write:user permissions
       - save changes and reinstall your app
    11.You also can choose file types, which you want delete and expiration
       period.


MAINTAINERS
-----------

 * Serge_Konst - https://www.drupal.org/u/serge_konst
 * Evgeny Leonov (hxdef) - https://www.drupal.org/u/hxdef
 * adci_contributor - https://www.drupal.org/u/adci_contributor

Supporting organization:

 * ADCI Solutions - https://www.drupal.org/adci-solutions
