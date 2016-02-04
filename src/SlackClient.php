<?php

/**
 * SlackClient is a client for sending messages to a slack endpoint
 * using Incoming Webhooks.
 *
 * @author Zsolt Fekete
 * @license https://opensource.org/licenses/MIT
 */
class SlackClient
{
    /**
     * Endpoint (URI) to send messages to.
     *
     * @var string
     */
    private $endpoint;

    /**
     * SlackClient constructor.
     *
     * @param $endpoint
     */
    public function __construct($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Initializes a messageObj to be sent.
     *
     * @param null|string $channel Name of the channel to send the messageObj to.
     * @return SlackMessage
     */
    public function to($channel = null)
    {
        return new SlackMessage($this, $channel);
    }

    /**
     * Sends a payload to the specified Slack Incoming Webhook endpoint.
     *
     * @param array $message
     * @return mixed
     */
    public function sendPayload($message)
    {
        // Prepare messageObj
        $message = 'payload=' . json_encode($message);

        // Open cURL connection
        $ch = curl_init($this->endpoint);

        // Set request
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);

        // Other cURL settings
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disable SSL check
        // TODO : Fix cert error and delete the following two lines
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        // Send messageObj and return result
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}