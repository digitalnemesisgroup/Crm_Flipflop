<?php

namespace App\Jobs;

use App\Models\MarketplaceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReleasePendingOrderInventory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     */
    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $order = MarketplaceOrder::with('items.product')->find($this->orderId);

        if (!$order) {
            return;
        }

        // If the order is still pending after the delay, mark it as failed and restore inventory
        if ($order->status === 'pending') {
            $order->status = 'failed';
            $order->save();

            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }
        }
    }
}
