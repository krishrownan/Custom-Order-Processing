<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterfaceFactory;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogSearchResultsInterfaceFactory;
use Smart\CustomOrderProcessing\Api\OrderStatusLogRepositoryInterface;
use Smart\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog as ResourceOrderStatusLog;
use Smart\CustomOrderProcessing\Model\ResourceModel\OrderStatusLog\CollectionFactory as OrderStatusLogCollectionFactory;

class OrderStatusLogRepository implements OrderStatusLogRepositoryInterface
{

    /**
     * @var OrderStatusLogCollectionFactory
     */
    protected $orderStatusLogCollectionFactory;

    /**
     * @var ResourceOrderStatusLog
     */
    protected $resource;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var OrderStatusLogInterfaceFactory
     */
    protected $orderStatusLogFactory;

    /**
     * @var OrderStatusLog
     */
    protected $searchResultsFactory;


    /**
     * @param ResourceOrderStatusLog $resource
     * @param OrderStatusLogInterfaceFactory $orderStatusLogFactory
     * @param OrderStatusLogCollectionFactory $orderStatusLogCollectionFactory
     * @param OrderStatusLogSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceOrderStatusLog $resource,
        OrderStatusLogInterfaceFactory $orderStatusLogFactory,
        OrderStatusLogCollectionFactory $orderStatusLogCollectionFactory,
        OrderStatusLogSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->orderStatusLogFactory = $orderStatusLogFactory;
        $this->orderStatusLogCollectionFactory = $orderStatusLogCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(OrderStatusLogInterface $orderStatusLog)
    {
        try {
            $this->resource->save($orderStatusLog);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderStatusLog: %1',
                $exception->getMessage()
            ));
        }
        return $orderStatusLog;
    }

    /**
     * @inheritDoc
     */
    public function get($orderStatusLogId)
    {
        $orderStatusLog = $this->orderStatusLogFactory->create();
        $this->resource->load($orderStatusLog, $orderStatusLogId);
        if (!$orderStatusLog->getId()) {
            throw new NoSuchEntityException(__('OrderStatusLog with id "%1" does not exist.', $orderStatusLogId));
        }
        return $orderStatusLog;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->orderStatusLogCollectionFactory->create();
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(OrderStatusLogInterface $orderStatusLog)
    {
        try {
            $orderStatusLogModel = $this->orderStatusLogFactory->create();
            $this->resource->load($orderStatusLogModel, $orderStatusLog->getOrderstatuslogId());
            $this->resource->delete($orderStatusLogModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderStatusLog: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($orderStatusLogId)
    {
        return $this->delete($this->get($orderStatusLogId));
    }
}
