<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ImportPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:postcodes {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to import postcode data from postcode.nl';

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
        $file = $this->argument('file');

        if (!\file_exists($file)) {
            throw new FileNotFoundException('Input file "'.$file.'" not found.');
        }
    }
}
