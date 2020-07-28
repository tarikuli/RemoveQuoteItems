<?php
/**
 * Copyright © Jewel@2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Jewel\RemoveQuoteItems\Test\Unit;

class RemoveQuoteItemsTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Is called before running a test
     */
    protected function setUp()
    {
        //setup
    }

    /**
     * The test itself, every test function must start with 'test'
     */
    public function testRemovequoteItems()
    {
        # 1) Login as any Customer
        # 2) Add Item to cart
        # 3) Logout from customer
        # 4) Add Item in cart as Guest
        # 5) Login as existing Customer
        # 6) Verify last add to cart item exist in cart. (Previous add to cart item should be remove)

        #$this->assertTrue(false);
    }
}

