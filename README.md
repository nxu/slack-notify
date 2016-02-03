# slack-notify

`slack-notify` is a very simple wrapper for the Slack Incoming Webhooks.

## Requirements
- PHP 5.4
- cURL

## Install
Clone the repo somewhere and load the library:

`require_once('path/to/slack-notify/Loader.php');`

The library does not use an autoloader, is not namespaced and does not come with support for installing with composer.
This makes it easy to integrate into legacy projects.

**Warning**: SSL check is disabled in the cURL settings. Check out `SlackClient.php` for more.

## Usage
1. Set up an incoming webhook [here](https://my.slack.com/services/new/incoming-webhook/)
2. Initialize the client:

    `$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');`

3. Send a plain or a formatted message:
    `$client->to('#test')->plainText('Some message')->send();`

## Examples

### Sending a simple plain text message

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client->to('#test')->plainText('Some message')->send();
```

![Example](http://i.imgur.com/N1qUNmq.png)

### Sending plain text message with basic formatting

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client
    ->to('#test')
    ->plainText([
        'text' => 'Some message with *bold* text',
        'icon_emoji' => ':robot_face:',
        'username' => 'Slack Robot'
    ])
    ->send();
```

![Example](http://i.imgur.com/LRQHFOv.png)

### Sending richly formatted messages ('attachments')

```php
$client = new SlackClient('https://hooks.slack.com/services/xx/xx/xxxx');
$client
    ->to('#test')
    ->formatted([
        'username' => 'Slack Robot',
        'icon_emoji' => ':robot_face:',
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