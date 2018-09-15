<?php

namespace Tests\Unit;

use App\Postcode;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ImportPostcodesTest extends TestCase
{
    public function testImportWithValidFile()
    {
        $this->assertCount(0, Postcode::all());

        Artisan::call('import:postcodes', ['file' => 'pcdata_test.csv']);

        $postcodes = Postcode::all();

        $this->assertCount(5, $postcodes);

        foreach ($postcodes as $postcode) {
            $this->assertSame(1, $postcode->getAttribute('id'));
        }
    }

    public function testImportFileNotFound()
    {
        $this->expectException('Illuminate\Contracts\Filesystem\FileNotFoundException');
        $this->expectExceptionMessage('Input file "does_not_exist.csv" not found.');

        Artisan::call('import:postcodes', ['file' => 'does_not_exist.csv']);
    }
}
