<?php
/**
 * Copyright © Smart Working All rights reserved.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class OrderStatusLog extends AbstractDb
{

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('order_status_update_log', 'entity_id');
    }
}
