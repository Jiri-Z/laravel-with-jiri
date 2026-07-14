<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    #[\Override]
    protected function buildMailMessage(mixed $url): MailMessage
    {
        $broker = config('auth.defaults.passwords');
        $broker = is_string($broker) ? $broker : 'users';
        $expire = config('auth.passwords.' . $broker . '.expire');
        $expireCount = is_int($expire) ? $expire : 60;

        return (new MailMessage)
            ->subject(__('Reset your password'))
            ->greeting(__('Hello!'))
            ->line(__('You are receiving this email because we received a password reset request for your account.'))
            ->action(__('Reset Password'), $url)
            ->line(__('This password reset link will expire in :count minutes.', ['count' => $expireCount]))
            ->line(__('If you did not request a password reset, no further action is required.'));
    }
}
