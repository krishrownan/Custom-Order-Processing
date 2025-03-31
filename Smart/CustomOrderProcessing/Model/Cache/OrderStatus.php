<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model\Cache;

class OrderStatus extends \Magento\Framework\Cache\Frontend\Decorator\TagScope
{

    const TYPE_IDENTIFIER = 'orderstatus_cache_tag';
    const CACHE_TAG = 'ORDERSTATUS_CACHE_TAG';

    /**
     * @param \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
     */
    public function __construct(
        \Magento\Framework\App\Cache\Type\FrontendPool $cacheFrontendPool
    ) {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }
}
