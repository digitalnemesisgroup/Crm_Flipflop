<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketplaceProduct;

class MarketplaceProductSeeder extends Seeder
{
    public function run(): void
    {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        MarketplaceProduct::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        MarketplaceProduct::create([
            'name' => 'WhatsApp CRM Automation Suite',
            'description' => 'Automate client updates, lead follow-ups, payment reminders, and instant status notifications via official WhatsApp API integration.',
            'price' => 1.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1611746872915-64382b5c76da?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        MarketplaceProduct::create([
            'name' => 'Advanced Financial & Payout Reports',
            'description' => 'Unlock deep revenue forecasting, employee performance analytics, custom tax summaries, and one-click PDF/Excel report exports.',
            'price' => 2.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        MarketplaceProduct::create([
            'name' => 'SMS & Transactional Email Alert Pack',
            'description' => 'Get 10,000 SMS credits and unlimited transactional email alerts for real-time task assignments and payout updates.',
            'price' => 3.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800&h=500'
        ]);

        MarketplaceProduct::create([
            'name' => 'Client Self-Service Portal & Invoicing',
            'description' => 'Provide a dedicated client portal for invoice downloads, project milestone tracking, and direct online payment links.',
            'price' => 4.00,
            'quantity' => 100,
            'image_url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=800&h=500'
        ]);
    }
}
