<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InitDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InitDb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->comment("Progress Started...");

        // همه جداول را کامل drop و از صفر بساز
        Artisan::call('migrate:fresh', [
            '--force' => true,
        ]);
        $this->comment("DB has been dropped and migrated fresh");

        Artisan::call('db:seed', [
            '--class' => "DatabaseSeeder",
            '--force' => true,
        ]);

        $this->info("Completed Successfully!");

    }
}
