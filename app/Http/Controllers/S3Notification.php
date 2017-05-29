<?php

namespace App\Http\Controllers;

use App\Jobs\IndexFiles;
use Aws\Sns\Exception\InvalidSnsMessageException;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;

class S3Notification extends Controller
{
    public function index()
    {
        // Instantiate the Message and Validator
        $message = Message::fromRawPostData();
        $validator = new MessageValidator();

        // Validate the message and log errors if invalid.
        try {
            $validator->validate($message);
        } catch (InvalidSnsMessageException $e) {
            // Pretend we're not here if the message is invalid.
            http_response_code(404);
            error_log('SNS Message Validation Error: ' . $e->getMessage());
            die();
        }

        // Check the type of the message and handle the subscription.
        if ($message['Type'] === 'SubscriptionConfirmation') {
            // Confirm the subscription by sending a GET request to the SubscribeURL
            file_get_contents($message['SubscribeURL']);
        }

        if ($message['Type'] === 'Notification') {
            $this->dispatch(new IndexFiles());
        }

        if ($message['Type'] === 'UnsubscribeConfirmation') {
            file_get_contents($message['SubscribeURL']);
        }
    }

}
