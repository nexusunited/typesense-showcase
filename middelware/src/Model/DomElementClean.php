<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Model;

final class DomElementClean implements DomElementCleanInterface
{
    public function clean(\DOMElement $domElement, string $removeItem): \DOMElement
    {
        /** @var \DOMElement $domElementClean */
        $domElementClean = $domElement->cloneNode(true);

        /** @var \DOMNodeList $elements */
        $elements = $domElementClean->getElementsByTagName($removeItem);
        $elementsToRemove = [];

        foreach ($elements as $element) {
            $elementsToRemove[] = $element;
        }

        foreach ($elementsToRemove as $element) {
            $element->parentNode->removeChild($element);
        }

        return $domElementClean;
    }
}
