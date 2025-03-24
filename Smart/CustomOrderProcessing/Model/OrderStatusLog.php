<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model;

use Magento\Framework\Model\AbstractModel;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;

class OrderStatusLog extends AbstractModel implements OrderStatusLogInterface
{

    /**
     * @inheritDoc
     */
    public function _construct()
    {
        $this->_init(\Smart\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog::class);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($orderId)
    {
        return $this->setData(self::ORDER_ID, $orderId);
    }

    /**
     * @inheritDoc
     */
    public function getOldStatus()
    {
        return $this->getData(self::OLD_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setOldStatus($oldStatus)
    {
        return $this->setData(self::OLD_STATUS, $oldStatus);
    }

    /**
     * @inheritDoc
     */
    public function getNewStatus()
    {
        return $this->getData(self::NEW_STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setNewStatus($newStatus)
    {
        return $this->setData(self::NEW_STATUS, $newStatus);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
