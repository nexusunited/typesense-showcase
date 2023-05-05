<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Model;

interface XmlConverterInterface
{
    public function getAttrValue(\DOMElement $item, string $attributeName): string;

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return array<string, string>
     */
    public function getLangAttrValue(\DOMElement $item, string $attributeName): array;

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return string[]
     */
    public function getListAttrValue(\DOMElement $item, string $attributeName): array;

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return array<string, string>
     */
    public function getCountryAttrValue(\DOMElement $item, string $attributeName): array;

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return \App\Product\Shared\ValueObject\XmlAttributeInfo[]
     */
    public function getCountryLikeAttrValue(\DOMElement $item, string $attributeName): array;
}
