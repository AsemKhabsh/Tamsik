<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Fatwa;

class FatwaAnsweredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $fatwa;

    /**
     * Create a new notification instance.
     */
    public function __construct(Fatwa $fatwa)
    {
        $this->fatwa = $fatwa;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // إشعارات داخل الموقع فقط (يمكن إضافة 'mail' عند تكوين البريد الإلكتروني)
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('تم الإجابة على سؤالك - موقع تمسك')
            ->view('emails.fatwa-answered', ['fatwa' => $this->fatwa]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'fatwa_id' => $this->fatwa->id,
            'fatwa_title' => $this->fatwa->title,
            'scholar_name' => $this->fatwa->scholar->name,
            'scholar_id' => $this->fatwa->scholar_id,
            'message' => 'تم الإجابة على سؤالك: ' . $this->fatwa->title,
            'url' => route('fatwas.show', $this->fatwa->id),
        ];
    }
}
