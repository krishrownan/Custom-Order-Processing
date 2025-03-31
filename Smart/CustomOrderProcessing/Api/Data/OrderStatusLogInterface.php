<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Api\Data;

interface OrderStatusLogInterface
{

    const ORDER_ID = 'order_id';
    const NEW_STATUS = 'new_status';
    const OLD_STATUS = 'old_status';
    const CREATED_AT = 'created_at';

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $orderId
     * @return \Smart\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setOrderId($orderId);

    /**
     * Get old_status
     * @return string|null
     */
    public function getOldStatus();

    /**
     * Set old_status
     * @param string $oldStatus
     * @return \Smart\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setOldStatus($oldStatus);

    /**
     * Get new_status
     * @return string|null
     */
    public function getNewStatus();

    /**
     * Set new_status
     * @param string $newStatus
     * @return \Smart\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setNewStatus($newStatus);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Smart\CustomOrderProcessing\OrderStatusLog\Api\Data\OrderStatusLogInterface
     */
    public function setCreatedAt($createdAt);
}
