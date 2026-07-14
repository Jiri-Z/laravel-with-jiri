<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends BaseVerifyEmail
{
    #[\Override]
    protected function buildMailMessage(mixed $url): MailMessage
    {
        $appName = config('app.name');

        return (new MailMessage)
            ->subject(__('Verify your email address'))
            ->greeting(__('Welcome to :app!', ['app' => is_string($appName) ? $appName : '']))
            ->line(__('Please click the button below to verify your email address and get started.'))
            ->action(__('Verify Email Address'), $url)
            ->line(__('If you did not create an account, no further action is required.'));
    }
}
