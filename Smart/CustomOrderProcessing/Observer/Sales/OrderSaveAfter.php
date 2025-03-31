<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Smart\CustomOrderProcessing\Model\OrderStatusLogRepository;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterfaceFactory;
use Psr\Log\LoggerInterface;

class OrderSaveAfter implements ObserverInterface
{

    /**
     * @var OrderStatusLogRepository
     */
    protected $orderStatusLogRepository;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @var OrderStatusLogInterfaceFactory
     */
    protected $orderStatusLogFactory;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param OrderStatusLogRepository $orderStatusLogRepository
     * @param OrderStatusLogInterfaceFactory $orderStatusLogFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderStatusLogRepository $orderStatusLogRepository,
        OrderStatusLogInterfaceFactory $orderStatusLogFactory,
        LoggerInterface $logger
    ) {
        $this->orderStatusLogRepository = $orderStatusLogRepository;
        $this->orderStatusLogFactory = $orderStatusLogFactory;
        $this->logger = $logger;
    }

    /**
     * Execute observer
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        $newStatus = $order->getStatus();
        $oldStatus = $observer->getEvent()->getOldStatus();

        if (!$order->getId() || !$newStatus || !$oldStatus) {
            $this->logger->error("Order or status information is missing.");
            throw new LocalizedException(__('Order or status information is missing.'));
        }

        try {
            $orderStatusLog = $this->orderStatusLogFactory->create();
            $orderStatusLog->setOrderId($order->getId());
            $orderStatusLog->setOldStatus($oldStatus);
            $orderStatusLog->setNewStatus($newStatus);
            $orderStatusLog->setCreatedAt(date('Y-m-d H:i:s'));
            $this->orderStatusLogRepository->save($orderStatusLog);
            $this->logger->info("Order status logged successfully for ID: " . $order->getId());
        } catch (\Exception $e) {
            $this->logger->error("Error logging order ID: " . $e->getMessage());
            throw new LocalizedException(__('Error logging order status: %1', $e->getMessage()));
        }
       
    }
}

