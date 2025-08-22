<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class ProjectProgressNotification extends Notification
{
    public $project;

    public function __construct($project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Progreso del proyecto ' . $this->project->name)
            ->body('¿Cuánto avanzaste? (Fin: ' . $this->project->end_date . ')')
            ->action('+0% (Procrastinar)', 'procrastinate')
            ->action('+10%', 'add10')
            ->action('+20%', 'add20')
            ->action('+30%', 'add30')
            ->action('+50%', 'add50')
            ->action('+100% (Completado)', 'complete')
            ->data(['project_id' => $this->project->id]);
    }
}