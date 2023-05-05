<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Model;

interface DomElementCleanInterface
{
    public function clean(\DOMElement $domElement, string $removeItem): \DOMElement;
}
