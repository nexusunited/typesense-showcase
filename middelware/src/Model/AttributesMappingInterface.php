<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Model;

use AboutYou\Cloud\AdminApi\Models\Attribute;

interface AttributesMappingInterface
{

    /**
     * @param \DOMElement $item
     * @param string[] $simpleNameList
     * @param string[] $simpleListNameList
     * @param string[] $localizedStringNameList
     *
     * @return \AboutYou\Cloud\AdminApi\Models\Attribute[]
     */
    public function map(\DOMElement $item, array $simpleNameList, array $simpleListNameList, array $localizedStringNameList): array;

    public function createAttribute(string $type, string $attributeName, mixed $value): Attribute;
}
