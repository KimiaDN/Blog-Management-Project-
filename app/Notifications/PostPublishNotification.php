<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostPublishNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $post;
    /**
     * Create a new notification instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/public/api/posts/'.$this->post->id);
        return (new MailMessage)
                    ->subject('A New Post Published')
                    ->line('user '. $this->post->user->username . ' published a new post with title '. $this->post->title)
                    ->action('you can see post at', $url)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'subject' => 'A New Post Published',
            'message' => 'user '. $this->post->user->username . ' published a new post with title '. $this->post->title,
            'post_id' => $this->post->id,
            'url' => url('/public/api/posts/'.$this->post->id),
        ];
    }
}
