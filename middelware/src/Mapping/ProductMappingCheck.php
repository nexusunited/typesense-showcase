<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import\Mapping;

use AboutYou\Cloud\AdminApi\Models\Product;
use App\Product\Business\Component\Import\Factory\ProductFactoryInterface;
use App\Product\Business\Component\Import\Model\AttributesMappingInterface;
use App\Product\Business\Component\Import\Model\DomElementCleanInterface;
use App\Product\Business\Component\Import\Model\XmlConverterInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

final class ProductMappingCheck
{
    private const SIMPLE_NAME_ATTR = [
        'mainOrderNumber',
        'articleKey',
        'articleType',
        'dispoStatus',
        'mainOrderNumberAdmediumProject',
        'mpZalando',
        'main_season',
        'ranking_item_sorting',
        'premium_club',
        'premiere_season',
        'direct_order',
        'manufacturCode',
    ];

    private const SIMPLE_LIST_NAME_ATTR = [
        'adMediumProjectName',
        'seasonName',
        'adMediumProjectKey',
    ];

    private const LOCALIZED_NAME_ATTR = [
        'color',
        'colorRange',
        'material',
        'articleName',
        'washComment',
        'articleForm',
        'pattern_style',
        'hem_wide_pants',
        'colour_world',
        'shortName',
    ];

    /**
     * @param \App\Product\Business\Component\Import\Model\AttributesMappingInterface $attributesMapping
     * @param \App\Product\Business\Component\Import\Model\XmlConverterInterface $xmlConverter
     * @param \App\Product\Business\Component\Import\Factory\ProductFactoryInterface $productFactory
     * @param \App\Product\Business\Component\Import\Mapping\ProductAttribute\ProductAttributeMappingInterface[] $attrMappers
     * @param \App\Product\Business\Component\Import\Mapping\Product\ProductMappingInterface[] $mappers
     */
    public function __construct(
        private readonly AttributesMappingInterface $attributesMapping,
        private readonly XmlConverterInterface      $xmlConverter,
        private readonly ProductFactoryInterface    $productFactory,
        private readonly DomElementCleanInterface   $domElementClean,
        #[TaggedIterator('product_attribute.mapping')]
        private readonly iterable                   $attrMappers,
        #[TaggedIterator('product.mapping')]
        private readonly iterable                   $mappers,
    )
    {
    }

    public function map(\DOMElement $article): Product
    {
        $scaleProduct = $this->productFactory->create($article);

        $cleanArticle = $this->domElementClean->clean($article, 'variant');
        $scaleProduct->name = $this->xmlConverter->getLangAttrValue($cleanArticle, 'articleName');

        $scaleAttributes = $this->attributesMapping->map(
            $cleanArticle,
            self::SIMPLE_NAME_ATTR,
            self::SIMPLE_LIST_NAME_ATTR,
            self::LOCALIZED_NAME_ATTR,
        );

        foreach ($this->attrMappers as $attrMapper) {
            $scaleAttributes = $attrMapper->map($scaleAttributes, $cleanArticle, $article);
        }

        $scaleProduct->attributes = $scaleAttributes;

        foreach ($this->mappers as $mapper) {
            $scaleProduct = $mapper->map($scaleProduct, $cleanArticle, $article);
        }

        return $scaleProduct;
    }
}
