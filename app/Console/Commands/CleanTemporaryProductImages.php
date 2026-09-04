<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanTemporaryProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:productImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temporary uploaded product images cleanup.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $catalog_path = config('catalog_path', '/catalog');
        $files = File::glob(public_path() . "/$catalog_path/temp_*");
        foreach ($files as $file) {
            if (filemtime($file) < Carbon::now()->subMinutes(config('temporary_product_image_lifetime', 60))->timestamp) {
                unlink($file);
            }
        }
    }
}
