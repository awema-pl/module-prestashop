<?php

namespace AwemaPL\Prestashop\Listeners;

use AwemaPL\Auth\Facades\Auth as AwemaAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EventSubscriber
{
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            static::class.'@handleRegistered'
        );
    }

    public function handleRegistered($event)
    {

    }
}
