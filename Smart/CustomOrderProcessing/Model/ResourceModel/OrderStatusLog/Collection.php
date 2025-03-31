<?php
/**
 * Copyright © Smart Working All rights reserved.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{

    /**
     * @inheritDoc
     */
    protected $_idFieldName = 'entity_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            \Smart\CustomOrderProcessing\Model\OrderStatusLog::class,
            \Smart\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog::class
        );
    }
}
