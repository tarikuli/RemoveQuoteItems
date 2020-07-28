<?php
/**
 * Copyright © Jewel@2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Jewel\RemoveQuoteItems\Test\Unit;

use Exception;
use Magento\Quote\Model\Quote;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Quote\Model\QuoteManagement;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Form\FormKey;
/**
 * Class RemoveQuoteItemsTest
 * @package Jewel\RemoveQuoteItems\Test\Unit
 */
class RemoveQuoteItemsTest extends \PHPUnit\Framework\TestCase
{

    const CUSTOMER_ID = 1;
    const PRODUCT_FIRST_ID = 1;
    const PRODUCT_SECOND_ID = 2;
    const LAST_SKU = 'Integration-Test-222';
    /**
     * @var ObjectManager $objectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Customer\Model\Session $session
     */
    protected $session;
    /**
     * @var Quote $quote
     */
    protected $quote;

    /**
     * Is called before running a test
     */
    protected function setUp()
    {
        $this->objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
//        $this->objectManager = new ObjectManager($this);
        $this->quote = $this->objectManager->create(Quote::class);
        $this->session = $this->objectManager->get(\Magento\Customer\Model\Session::class);

    }

    /**
     * The test itself, every test function must start with 'test'
     * @magentoDataFixture ../../../../app/code/Jewel/RemoveQuoteItems/_files/product_simple_without_source_msi.php
     * @magentoDataFixture ../../../../app/code/Jewel/RemoveQuoteItems/_files/customer.php
     * @magentoDbIsolation disabled
     * @throws Exception
     */
    public function testRemovequoteItems()
    {
        # 1) Login as any Customer
        $this->loginByCustomer();
        # 2) Add Item to cart
        $this->addProduct(self::PRODUCT_FIRST_ID);
        # 3) Logout from customer
        $this->logout();
        # 4) Add Item in cart as Guest
        $this->addProduct(self::PRODUCT_SECOND_ID);
        # 5) Login as existing Customer
        $this->loginByCustomer();
        # 6) Verify last add to cart item exist in cart. (Previous add to cart item should be remove)
        if (!$this->session->getCustomer()){
            $this->assertNull($this->session->getCustomer(), 'Customer is not login for test');
        }else{
            $this->assertGreaterThan(0,count($this->quote->getAllItems()), 'items can\'t find');
            $this->assertEquals(1, count($this->quote->getAllItems()), 'current qty is one');
            $sku = '';
            foreach ($this->quote->getAllItems() as $item ) {
                $sku = $item->getSku();
            }
            $this->assertEquals(self::LAST_SKU, $sku, 'last item correct');
        }
    }

    /**
     * @return mixed
     */
    public function getQuote()
    {
        if ($this->quote){
            return $this->quote;
        }
//        if ($this->session->isLoggedIn()){
//
//        }else{
//
//        }
        $quoteId = $this->objectManager->create(QuoteManagement::class)
            ->createEmptyCart();//createEmptyCartForCustomer
        $quote = $this->objectManager->create(Quote::class)
            ->load($quoteId);
        $this->quote = $quote;
        return $quote;
    }

    /**
     * @param $productId
     */
    public function addProduct($productId)
    {
        $product = $this->objectManager->create(Product::class)->load($productId);
        $obj = new \Magento\Framework\DataObject();
        $obj->setFormKey($this->objectManager->create(FormKey::class)->getFormKey());
        $obj->setProduct($product);
        $product->setQty(1);
//        $params = array(
//            'form_key' => $this->objectManager->create(FormKey::class)->getFormKey(),
//            'product' => $productId,
//            'qty'   =>1
//        );
        $this->getQuote()->addProduct($product,$obj );

    }

    protected function loginByCustomer()
    {
//        $session = $this->objectManager->get(Session::class);
//        $session->loginById($customerId);
        $this->session->loginById(self::CUSTOMER_ID);
        $this->session->regenerateId();
    }
    protected function logout()
    {
        $this->session->logout();
    }
}

