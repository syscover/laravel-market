<?php

use Illuminate\Database\Seeder;
use Syscover\Admin\Models\AttachmentMime;

class MarketAttachmentMimeSeeder extends Seeder
{
    public function run()
    {
        AttachmentMime::insert([
            ['resource_id' => 'market-product', 'mime' => 'image/jpeg'],
            ['resource_id' => 'market-product', 'mime' => 'image/png']
        ]);
    }
}

/*
 * Command to run:
 * php artisan db:seed --class="MarketAttachmentMimeSeeder"
 */