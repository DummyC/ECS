<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushNotification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    public function saveSubscription(Request $request)
    {
        // Save the subscription to the database
        $pushNotification = new PushNotification();
        $pushNotification->subscriptions = $request->all();

        $pushNotification->save();

        return response()->json($pushNotification->subscriptions);
    }

    public function getSubscriptions()
    {
        // Retrieve the subscriptions from the database
        $pushNotification = PushNotification::first();

        if ($pushNotification) {
            return response()->json($pushNotification->subscriptions);
        }

        return response()->json([]);
    }

    public function deleteSubscription(Request $request)
    {
        // Delete the subscription from the database
        $pushNotification = PushNotification::first();

        if ($pushNotification) {
            $subscriptions = $pushNotification->subscriptions;

            // Remove the subscription from the array
            $subscriptions = array_filter($subscriptions, function ($subscription) use ($request) {
                return $subscription['endpoint'] !== $request->input('endpoint');
            });

            // Update the subscriptions in the database
            $pushNotification->subscriptions = $subscriptions;
            $pushNotification->save();
        }

        return response()->json(['message' => 'Subscription deleted successfully']);
    }

    public function sendNotification(Request $request)
    {
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:dummy@dummycore.top', // can be a mailto: or your website address
                'publicKey' => config('webpush.vapid.public_key'), // (recommended) uncompressed public key P-256 encoded in Base64-URL
                'privateKey' => config('webpush.vapid.private_key'), // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
            ],
        ];

        $webPush = new WebPush($auth);

        $payload= json_encode([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'url' => $request->input('url'),
        ]);

        $pushNotifications = PushNotification::all();

        foreach ($pushNotifications as $pushNotification) {
            $webPush->sendOneNotification(
                Subscription::create($pushNotification->subscriptions),
                $payload,
                [
                    'TTL' => 5000, // Time to live in seconds
                    'urgency' => 'normal', // or 'high'
                    'topic' => 'webpush-notification', // Topic of the notification
                ]
                );
        }

        return response()->json(['message' => 'Notification sent successfully']);
    }
}
