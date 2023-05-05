<?php declare(strict_types=1);

namespace App\Model;

final class XmlConverter
{
    private const IGNORE_COUNTRY = [
        'NO' => true,
    ];

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return string[]
     */
    public function getListAttrValue(\DOMElement $item, string $attributeName): array
    {
        $value = [];
        $attributes = $item->getElementsByTagName('attribute');
        /** @var \DOMElement $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getAttribute('identificator') === $attributeName) {
                $value[] = $this->trim($attribute->nodeValue);
            }
        }

        return $value;
    }

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return array<string, string>
     */
    public function getLangAttrValue(\DOMElement $item, string $attributeName): array
    {
        $value = [];

        $attributes = $item->getElementsByTagName('attribute');
        /** @var \DOMElement $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getAttribute('identificator') === $attributeName) {

                if (isset(self::IGNORE_COUNTRY[$attribute->getAttribute('country')])) {
                    continue;
                }

                $shop2Lang = $attribute->getAttribute('language') . '_' . $attribute->getAttribute('country');
                $value[$shop2Lang] = $this->trim($attribute->nodeValue);
            }
        }

        return $value;
    }

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return array<string, string>
     */
    public function getCountryAttrValue(\DOMElement $item, string $attributeName): array
    {
        $value = [];

        $attributes = $item->getElementsByTagName('attribute');
        /** @var \DOMElement $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getAttribute('identificator') === $attributeName) {
                $country = $attribute->getAttribute('country');
                if (isset(self::IGNORE_COUNTRY[$country])) {
                    continue;
                }
                $value[$country] = $this->trim($attribute->nodeValue);
            }
        }

        return $value;
    }

    /**
     * @param \DOMElement $item
     * @param string $attributeName
     *
     * @return string
     */
    public function getAttrValue(\DOMElement $item, string $attributeName): string
    {
        $attributes = $item->getElementsByTagName('attribute');
        /** @var \DOMElement $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute->getAttribute('identificator') === $attributeName) {
                return $this->trim($attribute->nodeValue);
            }
        }

        return '';
    }

    private function trim(string $value): string
    {
        return trim($value);
    }
}
