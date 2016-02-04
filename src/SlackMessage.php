<?php

/**
 * SlackMessage is a an object representing a messageObj to be sent to a Slack
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
     * The channel to sent the message to.
     *
     * @var null|string
     */
    protected $channel;

    /**
     * The message object to be encoded.
     *
     * @var array
     */
    protected $messageObj = array();

    /**
     * The message attachments that need to be encoded.
     *
     * @var null|array
     */
    protected $attachments = null;

    /**
     * Initializes a new SlackMessage instance
     *
     * @param SlackClient $client Slack client to be used.
     * @param string|null $channel Channel to send the messageObj to (optional).
     */
    public function __construct(SlackClient $client, $channel = null)
    {
        $this->client = $client;
        $this->channel = $channel;
    }

    /**
     * Creates a messageObj to be sent.
     *
     * @param array $params
     * @return $this
     */
    public function message(array $params)
    {
        $this->messageObj = $params;
        return $this;
    }

    /**
     * Sends an instant message to the server. Returns the response.
     *
     * @param $message
     * @return mixed
     */
    public function instantMessage($message)
    {
        return $this->client->sendPayload(array(
            'channel' => $this->channel,
            'text' => $message
        ));
    }

    /**
     * Attaches an attachment to the messageObj.
     *
     * @param array $attachment
     * @return $this
     */
    public function attach(array $attachment)
    {
        // Check if null, if so, make it an array.
        if (is_null($this->attachments)) {
            $this->attachments = array();
        }

        // Attach attachment
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Constructs and sends the message. Returns the response of the server.
     *
     * @return mixed
     */
    public function send()
    {
        return $this->client->sendPayload($this->getMessageObject());
    }

    /**
     * Gets the messageObj as an object (PHP array) ready to be encoded as JSON.
     *
     * @return array
     */
    protected function getMessageObject()
    {
        $msg = $this->messageObj;

        // Check if channel specified, if not, add it
        if (!array_key_exists('channel', $msg)) {
            $msg['channel'] = $this->channel;
        }

        // Add attachments
        if (!is_null($this->attachments)) {
            $msg['attachments'] = $this->attachments;
        }

        return $msg;
    }
}