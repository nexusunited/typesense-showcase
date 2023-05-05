<?php declare(strict_types=1);

namespace App\Product\Business\Component\Import;

use App\Product\Business\Component\Import\Mapping\MasterMapping;
use App\Product\Business\Component\Import\Mapping\ProductMappingCheck;
use App\Product\Business\Component\Import\Mapping\VariantMapping;
use App\Product\Business\Component\Import\Model\ArticleConcatInterface;
use App\Product\Persistence\ProductEntityManagerInterface;
use App\Product\Shared\ValueObject\FilePathObject;
use App\Product\Shared\ValueObject\ProductApiObject;
use App\Product\Shared\ValueObject\ProductReferenceKeyObject;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProductNavision implements ProductNavisionInterface
{
    public function __construct(
        private readonly ProductEntityManagerInterface $productEntityManager,
        private readonly MasterMapping                 $masterMapping,
        private readonly ProductMappingCheck           $productMapping,
        private readonly VariantMapping                $variantMapping,
        private readonly MessageBusInterface           $messageBus,
        private readonly ArticleConcatInterface        $articleConcat,
    )
    {
    }

    public function import(FilePathObject $filePath): void
    {
        $dom = new \DOMDocument();
        $dom->load($filePath->filePath);

        $products = $dom->getElementsByTagName('product');

        foreach ($products as $product) {
            $master = $this->masterMapping->map($product);

            $articles = $product->getElementsByTagName('article');

            $productForDbList = [];
            /** @var \DOMElement $article */
            foreach ($articles as $article) {

                $product = $this->productMapping->map($article);

                $product->master = $master;

                $scaleVariantList = [];

                $variants = $article->getElementsByTagName('variant');
                foreach ($variants as $variant) {
                    $scaleVariantList[] = $this->variantMapping->map($variant);
                }

                $product->variants = $scaleVariantList;

                $productForDbList[] = $product;
            }

            $productForDbList = $this->articleConcat->concat($productForDbList, $master);

            foreach ($productForDbList as $productForDb) {

                $productReferenceKeyObject = new ProductReferenceKeyObject($productForDb->referenceKey);

                $this->productEntityManager->saveDivaInfo(
                    $productReferenceKeyObject,
                    $productForDb->jsonSerialize()
                );

                $this->messageBus->dispatch(new ProductApiObject($productReferenceKeyObject));
            }
        }
    }
}
