<?php

namespace Tests\Unit;

use App\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ImportPostcodesTest extends TestCase
{
    use RefreshDatabase;

    public function testImportWithValidFile()
    {
        $this->assertCount(0, Postcode::all());

        Artisan::call('import:postcodes', ['file' => 'pcdata_test.csv']);
        $postcodes = Postcode::all();

        $this->assertCount(5, $postcodes);

        /** @var Postcode $first */
        $first = $postcodes->first();
        $this->assertSame(1, $first->getAttribute('id'));
        $this->assertSame('1011AB', $first->getAttribute('postcode'));
        $this->assertSame(105, $first->getAttribute('lowest'));
        $this->assertSame(113, $first->getAttribute('highest'));
        $this->assertSame('odd', $first->getAttribute('type'));
        $this->assertSame('De Ruijterkade', $first->getAttribute('street'));
        $this->assertSame('Amsterdam', $first->getAttribute('city'));
        $this->assertSame('Amsterdam', $first->getAttribute('municipality'));
        $this->assertSame('Noord-Holland', $first->getAttribute('province'));

        /** @var Postcode $last */
        $last = $postcodes->last();
        $this->assertSame(5, $last->getAttribute('id'));
        $this->assertSame('1011AD', $last->getAttribute('postcode'));
        $this->assertSame(1, $last->getAttribute('lowest'));
        $this->assertSame(5, $last->getAttribute('highest'));
        $this->assertSame('odd', $last->getAttribute('type'));
        $this->assertSame('Oosterdokskade', $last->getAttribute('street'));
        $this->assertSame('Amsterdam', $last->getAttribute('city'));
        $this->assertSame('Amsterdam', $last->getAttribute('municipality'));
        $this->assertSame('Noord-Holland', $last->getAttribute('province'));
    }

    public function testImportFileNotFound()
    {
        $this->expectException('Illuminate\Contracts\Filesystem\FileNotFoundException');
        $this->expectExceptionMessage('Input file "does_not_exist.csv" not found.');

        Artisan::call('import:postcodes', ['file' => 'does_not_exist.csv']);
    }

    public function testImportWithEmptyFile()
    {
        $this->expectException('Symfony\Component\HttpFoundation\File\Exception\FileException');
        $this->expectExceptionMessage('Input file "pcdata_test_empty.csv" has no valid rows.');

        Artisan::call('import:postcodes', ['file' => 'pcdata_test_empty.csv']);
    }
}
