<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

use Magento\Catalog\Api\ProductRepositoryInterface;

$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // instance of object manager
/** @var ProductRepositoryInterface $productRepository */
$productRepository = $objectManager->create(ProductRepositoryInterface::class);

$productModel = $objectManager->create('\Magento\Catalog\Model\Product');
$productModel->setId(1);
$productModel->setSku('Integration-Test-999');
$productModel->setName('Sample Simple Product 001');
$productModel->setAttributeSetId(4);
$productModel->setStatus(1);
$productModel->setWeight(10);
$productModel->setVisibility(4);
$productModel->setTaxClassId(0);
$productModel->setTypeId('simple');
$productModel->setPrice(100);
$productModel->setQty(100);
$productModel->setWebsiteIds([1]);
$productModel->setMetaTitle('meta title');
$productModel->setMetaKeyword('meta keyword');
$productModel->setMetaDescription('meta description');
$productModel->setQty(100);
$productModel->setStockData(
    [
        'use_config_manage_stock' => 1,
        'qty' => 100,
        'is_qty_decimal' => 0,
        'is_in_stock' => 1,
    ]
);
$product = $productRepository->save($productModel);
$stockRegistry = $objectManager->create('Magento\CatalogInventory\Api\StockRegistryInterface');
/**
 * set inventory for stockdata
 */
$product = $productRepository->get('Integration-Test-999');
$sku = $product->getSku();
$stockItem = $stockRegistry->getStockItemBySku($sku);
$qty = 100;
$stockItem->setQty($qty);
$stockItem->setIsInStock((bool)$qty);
$stockRegistry->updateStockItemBySku($sku, $stockItem);

//second product

$productModel = $objectManager->create('\Magento\Catalog\Model\Product');
$productModel->setId(2);
$productModel->setSku('Integration-Test-222');
$productModel->setName('Sample Simple Product 001');
$productModel->setAttributeSetId(4);
$productModel->setStatus(1);
$productModel->setWeight(10);
$productModel->setVisibility(4);
$productModel->setTaxClassId(0);
$productModel->setTypeId('simple');
$productModel->setPrice(100);
$productModel->setQty(100);
$productModel->setWebsiteIds([1]);
$productModel->setMetaTitle('meta title');
$productModel->setMetaKeyword('meta keyword');
$productModel->setMetaDescription('meta description');
$productModel->setQty(100);
$productModel->setStockData(
    [
        'use_config_manage_stock' => 1,
        'qty' => 100,
        'is_qty_decimal' => 0,
        'is_in_stock' => 1,
    ]
);
$product = $productRepository->save($productModel);
$stockRegistry = $objectManager->create('Magento\CatalogInventory\Api\StockRegistryInterface');
/**
 * set inventory for stockdata
 */
$product = $productRepository->get('Integration-Test-222');
$sku = $product->getSku();
$stockItem = $stockRegistry->getStockItemBySku($sku);
$qty = 100;
$stockItem->setQty($qty);
$stockItem->setIsInStock((bool)$qty);
$stockRegistry->updateStockItemBySku($sku, $stockItem);

/**
 * index product IMS
 */
$indexerIds = [
    'catalog_product_price',
    'catalog_product_attribute',
    'cataloginventory_stock'
];
foreach ($indexerIds as $indexList) {
    $categoryIndexer = $objectManager->create('Magento\Framework\Indexer\IndexerRegistry')->get($indexList);
    $categoryIndexer->reindexList(array_unique([1,2]));
}
