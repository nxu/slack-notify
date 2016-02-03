<?php

/**
 * SlackMessageAttachmengt is the representation of a richly formatted attachment
 * sent along with a message using Incoming Webhooks.
 *
 * @author Zsolt Fekete
 * @license https://opensource.org/licenses/MIT
 */
class FormattedSlackMessage extends SlackMessage
{
    /**
     * Color used to color the border along the left side of the message attachment.
     * Can be a hex color code prefixed with #, or one of 'good', 'warning' or 'danger'.
     *
     * @var string|null
     */
    public $color = null;

    /**
     * A plain-text summary of the attachment.
     * @var string
     */
    public $fallback = '';

    /**
     * Optional text that appears above the attachment block.
     *
     * @var string|null
     */
    public $pretext = null;

    /**
     * The title is displayed as larger, bold text near the top of a message
     * attachment.
     *
     * @var null|string
     */
    public $title = null;

    /**
     * If provided, the title will be a hyperlink pointing to this address.
     *
     * @var null|string
     */
    public $title_link = null;

    /**
     * Fields are defined as an array, and hashes contained within it will be
     * displayed in a table inside the message attachment
     *
     * @var null|array
     */
    public $fields = null;

    /**
     * Initializes a new instance of the SlackMessageAttachment class.
     *
     * @param SlackMessage $base Base object to clone from.
     * @param array $params Parameters of the attachments.
     */
    public function __construct(SlackMessage $base, $params)
    {
        if ($base instanceof SlackMessage) {
            $this->cloneFromBase($base);
        }

        $this->processParams($params);
    }

    /**
     * Clones a SlackMessage into $this and sets variables.
     *
     * @param SlackMessage $base
     */
    protected function cloneFromBase(SlackMessage $base)
    {
        // Get object vars
        $objValues = get_object_vars($base);

        // Set instance variables
        foreach ($objValues as $key => $value) {
            $this->$key = $value;
        }

        // Convert text to fallback
        $this->fallback = $base->text;
    }

    /**
     * {@inheritDoc}
     */
    protected function getMessageObject()
    {
        $message = parent::getMessageObject();

        // Text is supposed to be inside the attachment only
        unset($message['text']);

        $attachment = [];

        // Create attachment. Clone to preserve properties.
        $attachmentObj = clone($this);

        // Set parent objects to null
        $attachmentObj->channel = null;
        $attachmentObj->username = null;
        $attachmentObj->icon_emoji = null;
        $attachmentObj->icon_url = null;

        // Create object (array)
        foreach (get_object_vars($attachmentObj) as $key => $value) {
            if (!empty($value)) {
                $attachment[$key] = $value;
            }
        }

        $message['attachments'] = [$attachment];

        return $message;
    }
}