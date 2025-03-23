<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $newStatus;
    public $oldStatus;
    public $customerName;

    /**
     * Create a new message instance.
     *
     * @param  mixed  $order
     * @param  string  $newStatus
     * @param  string  $oldStatus
     * @return void
     */
    public function __construct($order, $newStatus, $oldStatus)
    {
        $this->order = $order;
        $this->newStatus = $newStatus;
        $this->oldStatus = $oldStatus;
        $this->customerName = $order->user->first_name . ' ' . $order->user->last_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Order Status Has Been Updated')
            ->view('emails.emailorderstatus')
            ->with([
                'orderName' => ucfirst($this->order->orderName),
                'newStatus' => $this->newStatus,
                'oldStatus' => $this->oldStatus,
                'orderId' => $this->order->orderID,
                'customerName' => $this->customerName,
            ]);
    }
}
