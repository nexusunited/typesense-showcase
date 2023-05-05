<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Model;

use AboutYou\Cloud\AdminApi\Models\Attribute;

final class AttributesMapping implements AttributesMappingInterface
{
    public const SIMPLE = 'simple';
    public const SIMPLE_LIST = 'simpleList';
    public const LOCALIZED_STRING = 'localizedString';
    public const LOCALIZED_STRING_LIST = 'localizedStringList';

    public function __construct(
        private readonly XmlConverterInterface $xmlConverter
    )
    {
    }

    /**
     * @param \DOMElement $item
     * @param string[] $simpleNameList
     * @param string[] $simpleListNameList
     * @param string[] $localizedStringNameList
     *
     * @return \AboutYou\Cloud\AdminApi\Models\Attribute[]
     */
    public function map(\DOMElement $item, array $simpleNameList, array $simpleListNameList, array $localizedStringNameList): array
    {
        $scaleAttributes = [];

        $scaleAttributes = $this->setSimpleAttributes($simpleNameList, $item, $scaleAttributes);

        $scaleAttributes = $this->setListAttributes($simpleListNameList, $item, $scaleAttributes);

        $scaleAttributes = $this->setLocalizedStringAttributes($localizedStringNameList, $item, $scaleAttributes);

        return $scaleAttributes;
    }

    public function createAttribute(string $type, string $attributeName, mixed $value): Attribute
    {
        $attribute = new Attribute();
        $attribute->type = $type;
        $attribute->name = $attributeName;
        $attribute->value = $value;

        return $attribute;
    }

    /**
     * @param string[] $simpleNameList
     * @param \DOMElement $item
     * @param \AboutYou\Cloud\AdminApi\Models\Attribute[] $scaleAttributes
     *
     * @return \AboutYou\Cloud\AdminApi\Models\Attribute[]
     */
    private function setSimpleAttributes(array $simpleNameList, \DOMElement $item, array $scaleAttributes): array
    {
        foreach ($simpleNameList as $attributeName) {
            $value = $this->xmlConverter->getAttrValue($item, $attributeName);

            if ($value !== '') {
                $scaleAttributes[] = $this->createAttribute(
                    self::SIMPLE,
                    $attributeName,
                    $value
                );
            }
        }

        return $scaleAttributes;
    }

    /**
     * @param string[] $simpleListNameList
     * @param \DOMElement $item
     * @param \AboutYou\Cloud\AdminApi\Models\Attribute[] $scaleAttributes
     *
     * @return \AboutYou\Cloud\AdminApi\Models\Attribute[]
     */
    private function setListAttributes(array $simpleListNameList, \DOMElement $item, array $scaleAttributes): array
    {
        foreach ($simpleListNameList as $attributeName) {
            $value = $this->xmlConverter->getListAttrValue($item, $attributeName);

            if (!empty(array_filter($value))) {
                $scaleAttributes[] = $this->createAttribute(
                    self::SIMPLE_LIST,
                    $attributeName,
                    $value
                );
            }

        }

        return $scaleAttributes;
    }

    /**
     * @param string[] $localizedStringNameList
     * @param \DOMElement $item
     * @param \AboutYou\Cloud\AdminApi\Models\Attribute[] $scaleAttributes
     *
     * @return \AboutYou\Cloud\AdminApi\Models\Attribute[]
     */
    private function setLocalizedStringAttributes(array $localizedStringNameList, \DOMElement $item, array $scaleAttributes): array
    {
        foreach ($localizedStringNameList as $attributeName) {
            $value = $this->xmlConverter->getLangAttrValue($item, $attributeName);
            if (implode($value) !== '') {
                $scaleAttributes[] = $this->createAttribute(
                    self::LOCALIZED_STRING,
                    $attributeName,
                    $value
                );
            }
        }

        return $scaleAttributes;
    }
}
