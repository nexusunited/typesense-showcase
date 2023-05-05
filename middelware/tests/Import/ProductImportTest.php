<?php declare(strict_types=1);

namespace App\Tests\Import;


use App\Import\ProductImport;
use PHPUnit\Framework\TestCase;

final class ProductImportTest extends TestCase
{
    public function test()
    {
        $productImport = new ProductImport();
        $productImport->import();
    }
}
