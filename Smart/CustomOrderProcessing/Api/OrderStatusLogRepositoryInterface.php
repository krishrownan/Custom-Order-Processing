<?php
/**
 * Copyright © Smart Working All rights reserved.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderStatusLogRepositoryInterface
{

    /**
     * Save OrderStatusLog
     * @param \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderStatusLog
     * @return \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderStatusLog
    );

    /**
     * Retrieve OrderStatusLog
     * @param string $orderstatuslogId
     * @return \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($orderstatuslogId);

    /**
     * Retrieve OrderStatusLog matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete OrderStatusLog
     * @param \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderStatusLog
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface $orderStatusLog
    );

    /**
     * Delete OrderStatusLog by ID
     * @param string $orderstatuslogId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($orderstatuslogId);
}
