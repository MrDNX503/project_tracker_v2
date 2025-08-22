<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use NotificationChannels\WebPush\PushSubscription as WebPushSubscription;

class PushSubscription extends WebPushSubscription
{
    protected $fillable = ['user_id', 'endpoint', 'public_key', 'auth_token'];
}