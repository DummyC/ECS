<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\PushNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class CheckUpcomingEvents extends Command
{
    protected $signature = 'events:check-upcoming';
    protected $description = 'Check for upcoming events and send notifications';

    public function handle()
    {
        $this->info('Checking for upcoming events...');

        $events = Event::whereBetween('start', [
            Carbon::now(),
            Carbon::now()->addHours(12)
        ])->whereNull('notified_at')->get();

        if ($events->isEmpty()) {
            $this->info('No upcoming events found.');
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => config('webpush.vapid.subject'),
                'publicKey' => config('webpush.vapid.public_key'),
                'privateKey' => config('webpush.vapid.private_key'),
            ],
        ];

        $webPush = new WebPush($auth);

        foreach ($events as $event) {
            $this->sendEventNotification($event, $webPush);
        }

        $this->info('Notifications sent successfully.');
    }

    private function sendEventNotification($event, $webPush)
    {
        $payload = json_encode([
            'title' => 'Upcoming Event: ' . $event->title,
            'body' => "Event starts in 12 hours\n" . $event->description,
            // 'url' => route('user.dashboard', ['event' => $event->id] ),
            'data' => [
                'url' => route('user.dashboard', ['event' => $event->id])
            ],
            'icon' => '/ecs_notification-icon.png',
        ]);

        $pushNotifications = PushNotification::all();

        foreach ($pushNotifications as $notification) {
            try {
                $webPush->sendOneNotification(
                    Subscription::create($notification->subscriptions),
                    $payload,
                    ['TTL' => 5000]
                );
            } catch (\Exception $e) {
                $this->error("Failed to send notification: {$e->getMessage()}");
            }
        }

        $event->update(['notified_at' => now()]);
    }
}
