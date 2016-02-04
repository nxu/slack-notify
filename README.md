# slack-notify

`slack-notify` is a very simple wrapper for encoding and sending messages
using the Slack Incoming Webhooks.

Its goal is to be as lightweight as possible. It means there is no real
validating or parsing, the library simply provides a nice API for encoding
and sending a payload to a specified endpoint using the Slack Incoming Webhook.

## Requirements
- PHP 5.1
- cURL

## Install
Clone the repo somewhere and load the library:

`require_once('path/to/slack-notify/src/Loader.php');`

This version of the library does not use an autoloader, is not namespaced and does not come with support for installing with composer.
This makes it easy to integrate into legacy projects.

**Warning**: SSL check is disabled in the cURL settings. Check out `SlackClient.php` for more.

## Usage
1. Set up an incoming webhook [here](https://my.slack.com/services/new/incoming-webhook/)
2. Initialize the client:

    `$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');`

3. Send a message:
    `$client->to('#test')->instantMessage('Hello, world!');`

## Examples

### Sending a simple message
Even "plain text" messages can be formatted using Slack's markdown implementation.

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client->to('#test')->instantMessage('Hello, *world*!');
```

![Example](http://i.imgur.com/ND6oWwv.png)

If you use `instantMessage`, the default webhook username and icon will be used.

### Sending a simple message with custom user name and icon

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client
    ->to('#test')
    ->message([
        'text' => 'Some message with *bold* text',
        'icon_emoji' => ':robot_face:',
        'username' => 'Slack Robot'
    ])
    ->send();
```

![Example](http://i.imgur.com/LRQHFOv.png)

### Sending messages with attachments

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client
    ->to('#test')
    ->message([
        'username' => 'Slack Robot',
        'icon_emoji' => ':robot_face:'
    ])
    ->attach([
        'pretext' => 'A new ticket has been added for one of your projects',
        'title' => 'Ticket #1234 - Error on the user registration form',
        'title_link' => 'http://example.com/admin/tickets/1234',
        'text' => 'This can be a longer description of the ticket.',
        'color' => 'warning',
        'fields' => [
            [
                'title' => 'Project',
                'value' => 'Example project',
                'short' => true
            ],
            [
                'title' => 'Deadline',
                'value' => '2016. 01. 01.',
                'short' => true
            ]
        ]
    ])
    ->send();
```

![Example](http://i.imgur.com/pqJr2EJ.png)

You can attach multiple attachments by chaining the attach() methods.

### Changelog
#### 2016. 02. 04. (v1.0)
- Refactored the code
- Simplified the library radically
- Changed code to use `array()` instead of the `[]` notation to support older PHP versions

#### 2016. 02. 03. (v0.1)
- Very first release.

### License
`slack-notify` is licensed under The MIT License. See LICENSE for details.