<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ApiResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     * Uses FRONTEND_PASSWORD_RESET_URL so the link points to your app (e.g. React/Vue).
     */
    public function toMail($notifiable): MailMessage
    {
        $url = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Password')
            ->line('You are receiving this because we received a password reset request.')
            ->action('Reset Password', $url)
            ->line('This link will expire in ' . config('auth.passwords.users.expire') . ' minutes.')
            ->line('If you did not request this, ignore this email.');
    }

    protected function resetUrl($notifiable): string
    {
        $base = config('app.frontend_password_reset_url') ?: config('app.url');
        $frontendUrl = rtrim($base, '/');
        return $frontendUrl . '?token=' . $this->token . '&email=' . urlencode($notifiable->getEmailForPasswordReset());
    }
}
