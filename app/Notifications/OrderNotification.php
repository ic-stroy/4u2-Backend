<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification
{
    use Queueable;

    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'order_id'=>$this->data['order_id'],
            'order_detail_id'=>$this->data['order_detail_id'],
            'order_all_price'=>$this->data['order_all_price'],
            'user'=>$this->data['receiver_name'],
            'product_name'=>$this->data['product']['name'],
            'product_images'=>$this->data['product']['images'],
        ];
    }
}
