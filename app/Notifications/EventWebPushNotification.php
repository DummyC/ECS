<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class EventWebPushNotification extends Notification
{
    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Upcoming Event: ' . $this->event->title)
            ->icon('/ecs_notification-icon.png')
            ->body('Event starts in 12 hours')
            ->action('View Event', 'view_event')
            ->data(['event_id' => $this->event->id]);
    }
}
