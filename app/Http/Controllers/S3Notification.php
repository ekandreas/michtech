<?php

namespace App\Http\Controllers;

use App\Jobs\IndexFiles;
use Aws\Sns\Message;
use Aws\Sns\MessageValidator;
use GuzzleHttp\Client;

class S3Notification extends Controller
{
    public function index()
    {

        // Make sure the request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die;
        }

        try {
            // Create a message from the post data and validate its signature
            $message = Message::fromRawPostData();
            $validator = new MessageValidator();
            $validator->validate($message);
        } catch (\Exception $e) {
            // Pretend we're not here if the message is invalid
            http_response_code(404);
            die;
        }

        if ($message->get('Type') === 'SubscriptionConfirmation') {
            // Send a request to the SubscribeURL to complete subscription
            (new Client)->get($message->get('SubscribeURL'))->send();
        } elseif ($message->get('Type') === 'Notification') {
            // Do something with the notification
            $this->dispatch(new IndexFiles());
        }

    }

}
