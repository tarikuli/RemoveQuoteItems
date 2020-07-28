<?php
/**
 * Copyright © Jewel@2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Jewel\RemoveQuoteItems\Plugin\Magento\Quote\Model;

use Magento\Quote\Model\Quote;

class RemoveB2BQuoteItems extends Quote
{

    /***
     * @var int $_b2bWebSideId
     */
    protected $_b2bWebSideId = 2; # You need to change this value according your local setting.

    public function beforeMerge(
        \Magento\Quote\Model\Quote $subject,
        $quote
    ) {
        if($subject->_storeManager->getStore()->getWebsiteId() == $this->_b2bWebSideId){
            foreach ($subject->getAllItems() as $existingQuoteItem) {
                $existingQuoteItem->delete();
            }
        }
        return [$quote];
    }
}

