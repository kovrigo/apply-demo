<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApplyDeploy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apply:deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy script';

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
        // TODO: make forks of the packages
        // Increase time to optimize images
        $main = file_get_contents('./vendor/spatie/image-optimizer/src/OptimizerChain.php');
        $main = preg_replace_callback("/timeout = 60;/m", function ($matches) {
            return "timeout = 6000;";
        }, $main);
        file_put_contents('./vendor/spatie/image-optimizer/src/OptimizerChain.php', $main);
        // Add "jpg" as valid image MIME-type
        $main = file_get_contents('./vendor/laravel/framework/src/Illuminate/Validation/Concerns/ValidatesAttributes.php');
        $main = str_replace("'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'", "'jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'", $main);
        file_put_contents('./vendor/laravel/framework/src/Illuminate/Validation/Concerns/ValidatesAttributes.php', $main);


    }
}
