<?php

declare(strict_types=1);

namespace Smart\CustomOrderProcessing\Test\Integration;

use Magento\TestFramework\Helper\Bootstrap;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;
use Smart\CustomOrderProcessing\Model\OrderStatusUpdateManagement;

class OrderStatusUpdateManagementTest extends TestCase
{
    private ?OrderStatusUpdateManagement $orderStatusUpdateManagement;
    private ?OrderRepositoryInterface $orderRepository;

    protected function setUp(): void
    {
        $objectManager = Bootstrap::getObjectManager();
        $this->orderStatusUpdateManagement = $objectManager->create(OrderStatusUpdateManagement::class);
        $this->orderRepository = $objectManager->create(OrderRepositoryInterface::class);
    }

    /**
     * @magentoDataFixture Magento/Sales/_files/order.php
     */
    public function testPostOrderStatusUpdate(): void
    {
        // Load a test order
        $orderIncrementId = '100000001'; // Predefined in Magento/Sales/_files/order.php
        $newStatus = 'processing';

        // Call method under test
        $response = $this->orderStatusUpdateManagement->postOrderStatusUpdate($orderIncrementId, $newStatus);

        // Reload order to check the updated status
        $order = $this->orderRepository->get($response[0]['order_id']);
        
        // Assertions
        $this->assertEquals($newStatus, $order->getStatus(), 'Order status was not updated correctly.');
        $this->assertEquals($orderIncrementId, $response[0]['order_id'], 'Order ID mismatch.');
    }

    public function testPostOrderStatusUpdateWithInvalidOrder(): void
    {
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('Order with increment ID 999999999 not found.');

        $this->orderStatusUpdateManagement->postOrderStatusUpdate('999999999', 'processing');
    }
}
