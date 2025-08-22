<?php

namespace App\Console;

use App\Models\Project;
use App\Notifications\ProjectProgressNotification;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use NotificationChannels\WebPush\WebPushChannel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            \Log::info('Scheduler running');
            $projects = Project::where('progress', '<', 100)
                ->where('status', '!=', 'completed')
                ->get();
            \Log::info('Found ' . $projects->count() . ' projects');
            foreach ($projects as $project) {
                $subscriptions = \App\Models\PushSubscription::whereNull('user_id')->get();
                \Log::info('Found ' . $subscriptions->count() . ' subscriptions for project ' . $project->id);
                foreach ($subscriptions as $subscription) {
                    WebPushChannel::sendNotification($subscription, new ProjectProgressNotification($project));
                }
            }
        })->everyMinute(); // Cambia a daily() tras pruebas
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}