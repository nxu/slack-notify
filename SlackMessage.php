<?php

/**
 * SlackMessage is a an object representing a message to be sent to a Slack
 * channel using Incoming Webhooks.
 *
 * @author Zsolt Fekete
 * @license https://opensource.org/licenses/MIT
 */
class SlackMessage
{
    /**
     * Slack client.
     *
     * @var SlackClient
     */
    protected $client;

    /**
     * Value indicating whether the message is formatted instead of plain text.
     *
     * @var bool
     */
    protected $isFormatted;

    /**
     * Channel to send the message to.
     *
     * @var string|null
     */
    public $channel;

    /**
     * Name to be displayed.
     *
     * @var string|null
     */
    public $username;

    /**
     * Message body. Can be plain or formatted.
     *
     * @var string
     */
    public $text;

    /**
     * Emoji to be used as an icon.
     *
     * @var string
     */
    public $icon_emoji;

    /**
     * URL of the icon to be used.
     *
     * @var string
     */
    public $icon_url;

    /**
     * Initializes a new SlackMessage instance
     *
     * @param SlackClient $client Slack client to be used.
     * @param string|null $channel Channel to send the message to (optional).
     */
    public function __construct(SlackClient $client, $channel = null)
    {
        $this->client = $client;
        $this->channel = $channel;
    }

    /**
     * Creates a plain text message (can be formatted with markdown).
     *
     * @param array $params
     * @return $this
     */
    public function plainText($params)
    {
        // Check if only message was provided
        if (is_string($params)) {
            $params = [
                'text' => $params
            ];
        }

        $this->processParams($params);
        return $this;
    }

    /**
     * Creates a formatted message.
     *
     * @param array $params
     * @return FormattedSlackMessage
     */
    public function formatted($params)
    {
        return new FormattedSlackMessage($this, $params);
    }

    /**
     * Sends the message.
     *
     * @return mixed
     */
    public function send()
    {
        return $this->client->sendPayload($this->getMessageObject());
    }

    /**
     * Processes parameters and sets the corresponding properties.
     *
     * @param array $params
     */
    protected function processParams($params)
    {
        foreach (get_object_vars($this) as $key => $value) {
            if (!empty($params[$key])) {
                $this->$key = $params[$key];
            }
        }
    }

    /**
     * Gets the message as an object (PHP array) ready to be encoded as JSON.
     *
     * @return array
     */
    protected function getMessageObject()
    {
        // Create basic message manually
        $message = [];

        if (!empty($this->text)) {
            $message['text'] = $this->text;
        } else {
            throw new InvalidArgumentException("Text is required");
        }

        if (!empty($this->username)) {
            $message['username'] = $this->username;
        }

        if (!empty($this->icon_emoji)) {
            $message['icon_emoji'] = $this->icon_emoji;
        }

        if (!empty($this->icon_url)) {
            $message['icon_url'] = $this->icon_emoji;
        }

        if (!empty($this->channel)) {
            $message['channel'] = $this->channel;
        }

        return $message;
    }
}