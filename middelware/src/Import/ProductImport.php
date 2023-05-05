<?php declare(strict_types=1);

namespace App\Import;

use App\Model\XmlConverter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpClient\HttplugClient;
use Typesense\Client;

final class ProductImport
{
    private XmlConverter $xmlConverter;

    private const ATTR = [
        'articleName',
        'color',
        'material',
        'shortName',
        'ranking_item_sorting',
        'recommendations',
        'brand',
    ];

    private Client $client;

    public function __construct()
    {
        $this->xmlConverter = new XmlConverter();

        $this->client = new Client(
            [
                'api_key' => 'xyz',
                'nodes' => [
                    [
                        'host' => 'localhost',
                        'port' => '8108',
                        'protocol' => 'http',
                    ],
                ],
                'client' => new HttplugClient(),
            ]
        );
    }

    public function import()
    {
        $this->create();

        $finder = new Finder();
        $finder->in(__DIR__ . '/../../file/');

        foreach ($finder as $item) {
            if(!$item->isFile()) {
                continue;
            }
            $dom = new \DOMDocument();
            $dom->load($item->getRealPath());

            $products = $dom->getElementsByTagName('product');

            foreach ($products as $key => $product) {

                $category = $this->xmlConverter->getAttrValue($product, 'webshopBaseCategoryName');

                $categoryList = explode(',', $category);
                $productData = [
                    'category' => $categoryList,
                    'url' => 'https://picsum.photos/400/400?random=' . $key,
                ];

                foreach ($categoryList as $key2 => $categoryName) {
                    $productData['categoryLevel' . $key2] = $categoryName;
                }
                $articles = $product->getElementsByTagName('article');
                foreach ($articles as $article) {
                    $productData['id'] = $this->xmlConverter->getAttrValue($article, 'articleKey');
                    $productData['artNum'] = (int)$this->xmlConverter->getAttrValue($article, 'articleKey');

                    foreach (self::ATTR as $attrName) {
                        $productData[$attrName] = $this->xmlConverter->getAttrValue($article, $attrName);
                    }
                }

                $this->client->collections['products']->documents->create($productData);
            }
        }
    }

    private function create()
    {
        $client = $this->client;

        try {
            $client->collections['products']->delete();
        } catch (\Exception $e) {
            // Don't error out if the collection was not found
        }

        $client->collections->create(
            [
                'name' => 'products',
                'fields' => [
                    [
                        'name' => 'articleName',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'category',
                        'type' => 'string[]',
                        'facet' => true,
                    ],
                    [
                        'name' => 'categoryLevel0',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'categoryLevel1',
                        'type' => 'string',
                        "optional" => true,
                        'facet' => true,
                    ],
                    [
                        'name' => 'categoryLevel2',
                        'type' => 'string',
                        "optional" => true,
                        'facet' => true,
                    ],
                    [
                        'name' => 'categoryLevel3',
                        'type' => 'string',
                        "optional" => true,
                    ],
                    [
                        'name' => 'categoryLevel4',
                        'type' => 'string',
                        "optional" => true,
                    ],
                    [
                        'name' => 'color',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'artNum',
                        'type' => 'int32',
                    ],
                    [
                        'name' => 'material',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'shortName',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'recommendations',
                        'type' => 'string',
                    ],
                    [
                        'name' => 'brand',
                        'type' => 'string',
                        'facet' => true,
                    ],
                    [
                        'name' => 'url',
                        'type' => 'string',
                    ],
                ],
            ]
        );

        $client->collections['products']->retrieve();
        $client->collections->retrieve();
    }
}
