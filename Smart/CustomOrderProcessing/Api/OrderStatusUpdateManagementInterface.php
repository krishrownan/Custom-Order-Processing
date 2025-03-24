<?php
/**
 * Copyright © Smart Working All rights reserved.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Api;

interface OrderStatusUpdateManagementInterface
{

    /**
     * POST for OrderStatusUpdate api
     * @param string $orderIncrementId
     * @param string $orderStatus
     * @return string
     */
    public function postOrderStatusUpdate($orderIncrementId, $orderStatus);
}

