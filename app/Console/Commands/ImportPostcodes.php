<?php

namespace App\Console\Commands;

use App\Postcode;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(
            function () {
                $file = $this->argument('file');

                if (!\file_exists($file)) {
                    throw new FileNotFoundException('Input file "'.$file.'" not found.');
                }

                $types = [
                    '0' => 'odd',
                    '1' => 'even',
                    '2' => 'houseboats',
                    '3' => 'trailers',
                    ' ' => 'vacant',
                ];
                $handle = fopen($file, 'r');
                Postcode::query()->truncate();

                while (false !== $row = fgetcsv($handle, 1000, ',')) {
                    list($id, $numbers, $letters, $lowest, $highest, $type, , , $street, , , $city, , , $municipality, , $province) = $row;

                    Postcode::create(
                        [
                            'id' => $id,
                            'postcode' => $numbers.$letters,
                            'lowest' => $lowest,
                            'highest' => $highest,
                            'type' => $types[$type],
                            'street' => $street,
                            'city' => $city,
                            'municipality' => $municipality,
                            'province' => $province
                        ]
                    );
                }
            }
        );
    }
}
