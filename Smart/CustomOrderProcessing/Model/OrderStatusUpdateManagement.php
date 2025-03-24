<?php
/**
 * Copyright © Smart Working All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Psr\Log\LoggerInterface;
use Smart\CustomOrderProcessing\Api\OrderStatusUpdateManagementInterface;

class OrderStatusUpdateManagement implements OrderStatusUpdateManagementInterface
{

    protected OrderRepositoryInterface $orderRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected EventManager $eventManager;
    protected OrderSender $orderSender;
    protected LoggerInterface $logger;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param EventManager $eventManager
     * @param OrderSender $orderSender
     * @param LoggerInterface $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        EventManager $eventManager,
        OrderSender $orderSender,
        LoggerInterface $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->eventManager = $eventManager;
        $this->orderSender = $orderSender;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     * @param string $orderIncrementId
     * @param string $orderStatus
     * @return string
     */
    public function postOrderStatusUpdate($orderIncrementId, $orderStatus)
    {
        $response = [];

        if (empty($orderIncrementId) || empty($orderStatus)) {
            throw new InputException(__('Order increment ID and status are required.'));
        }
        
         // Get Order ID using OrderRepository (without Object Manager)
        $orderId = $this->getOrderIdByIncrementId($orderIncrementId);
         if (!$orderId) {
             throw new NoSuchEntityException(__('Order with increment ID %1 not found.', $orderIncrementId));
         }
       
        try {
            // Load order using OrderRepository
            $order = $this->orderRepository->get($orderId);
            $oldStatus = $order->getStatus();
            $order->setState($orderStatus);
            $order->setStatus($orderStatus);
            $orderSave = $this->orderRepository->save($order);
            if ($orderStatus === 'shipped') {
                $this->orderSender->send($order);
            }
            $this->eventManager->dispatch('sales_order_save_after', ['order' => $orderSave, 'old_status' =>  $oldStatus]);
            $this->logger->info("Order status updated successfully for ID: " . $orderIncrementId);
            $response['response'] = [
                'order_id' => $orderSave->getId(),
                'order_status' => $orderSave->getStatus(),
                'message' => 'Order status updated successfully for ID: ' . $orderIncrementId
            ];
        } catch (\Exception $e) {
            $this->logger->error("Error updating order: " . $e->getMessage());
            throw new LocalizedException(__('Error updating order status: %1', $e->getMessage()));
        }

        return $response;
    }

    /**
     * Get Order ID by Increment ID using OrderRepositoryInterface
     * @param string $incrementId
     * @return string|null
     */
    private function getOrderIdByIncrementId(string $incrementId): ?string
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('increment_id', $incrementId)
            ->setPageSize(1)
            ->create();

        $orderList = $this->orderRepository->getList($searchCriteria)->getItems();

        if (!empty($orderList)) {
           
            $order = reset($orderList);
            return $order->getEntityId();
        }

        return null;
    }
}

