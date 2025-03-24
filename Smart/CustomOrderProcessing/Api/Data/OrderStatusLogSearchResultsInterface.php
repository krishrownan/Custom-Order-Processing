<?php
/**
 * Copyright © Smart Working All rights reserved.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Api\Data;

interface OrderStatusLogSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get OrderStatusLog list.
     * @return \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface[]
     */
    public function getItems();

    /**
     * Set order_id list.
     * @param \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
