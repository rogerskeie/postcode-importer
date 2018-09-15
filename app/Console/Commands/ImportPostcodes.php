<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

                $rowCount = count(file($file, FILE_SKIP_EMPTY_LINES));

                if ($rowCount === 0) {
                    throw new FileException('Input file "'.$file.'" has no valid rows.');
                }

                $handle = fopen($file, 'r');
                DB::table('postcodes')->truncate();

                $this->output->title('Importing postcode data:');
                $this->output->progressStart($rowCount);

                while (false !== $row = fgetcsv($handle, 1000, ',')) {
                    $this->importRow($row);
                    $this->output->progressAdvance();
                }

                $this->output->progressFinish();
                $this->output->success($rowCount.' rows imported');
            }
        );
    }

    private function importRow(array $row)
    {
        $types = [
            '0' => 'odd',
            '1' => 'even',
            '2' => 'houseboats',
            '3' => 'trailers',
            ' ' => 'vacant',
        ];
        list($id, $numbers, $letters, $lowest, $highest, $type, , , $street, , , $city, , , $municipality, , $province) = $row;

        DB::table('postcodes')->insert(
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
