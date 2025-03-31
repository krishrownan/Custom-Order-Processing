<?php

namespace Smart\CustomOrderProcessing\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Psr\Log\LoggerInterface;
use Smart\CustomOrderProcessing\Model\OrderStatusUpdateManagement;
use Magento\Sales\Model\Order;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class OrderStatusUpdateManagementTest extends TestCase
{
    private $orderRepositoryMock;
    private $searchCriteriaBuilderMock;
    private $eventManagerMock;
    private $orderSenderMock;
    private $loggerMock;
    private $orderMock;
    private $orderStatusUpdateManagement;

    protected function setUp(): void
    {
        $this->orderRepositoryMock = $this->createMock(OrderRepositoryInterface::class);
        $this->searchCriteriaBuilderMock = $this->createMock(SearchCriteriaBuilder::class);
        $this->eventManagerMock = $this->createMock(EventManager::class);
        $this->orderSenderMock = $this->createMock(OrderSender::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->orderMock = $this->createMock(Order::class);

        $this->orderStatusUpdateManagement = new OrderStatusUpdateManagement(
            $this->orderRepositoryMock,
            $this->searchCriteriaBuilderMock,
            $this->eventManagerMock,
            $this->orderSenderMock,
            $this->loggerMock
        );
    }

    public function testPostOrderStatusUpdateThrowsExceptionForEmptyInputs()
    {
        $this->expectException(InputException::class);
        $this->orderStatusUpdateManagement->postOrderStatusUpdate('', '');
    }

    public function testPostOrderStatusUpdateThrowsExceptionForNonExistingOrder()
    {
        $this->orderRepositoryMock
            ->method('getList')
            ->willReturn(new \Magento\Framework\Api\SearchResults()); // No orders found

        $this->expectException(NoSuchEntityException::class);
        $this->orderStatusUpdateManagement->postOrderStatusUpdate('000000123', 'processing');
    }

    public function testPostOrderStatusUpdateSuccess()
    {
        $orderIncrementId = '000000123';
        $orderStatus = 'processing';

        // Mock order entity
        $this->orderMock->method('getEntityId')->willReturn(1);
        $this->orderMock->method('getStatus')->willReturn('pending');
        $this->orderMock->expects($this->once())->method('setState')->with($orderStatus);
        $this->orderMock->expects($this->once())->method('setStatus')->with($orderStatus);
        $this->orderMock->method('getId')->willReturn(1);

        // Mock order repository behavior
        $searchResultsMock = $this->createMock(\Magento\Framework\Api\SearchResults::class);
        $searchResultsMock->method('getItems')->willReturn([$this->orderMock]);
        $this->orderRepositoryMock->method('getList')->willReturn($searchResultsMock);
        $this->orderRepositoryMock->method('get')->willReturn($this->orderMock);
        $this->orderRepositoryMock->method('save')->willReturn($this->orderMock);

        // Expect event dispatch
        $this->eventManagerMock->expects($this->once())->method('dispatch')->with(
            'sales_order_save_after',
            ['order' => $this->orderMock, 'old_status' => 'pending']
        );

        // Run test
        $response = $this->orderStatusUpdateManagement->postOrderStatusUpdate($orderIncrementId, $orderStatus);

        $this->assertIsArray($response);
        $this->assertEquals(1, $response['response']['order_id']);
        $this->assertEquals($orderStatus, $response['response']['order_status']);
    }

    public function testPostOrderStatusUpdateTriggersEmailForShippedOrders()
    {
        $orderIncrementId = '000000456';
        $orderStatus = 'shipped';

        // Mock order entity
        $this->orderMock->method('getEntityId')->willReturn(1);
        $this->orderMock->method('getStatus')->willReturn('pending');
        $this->orderMock->expects($this->once())->method('setState')->with($orderStatus);
        $this->orderMock->expects($this->once())->method('setStatus')->with($orderStatus);
        $this->orderMock->method('getId')->willReturn(1);

        // Mock order repository behavior
        $searchResultsMock = $this->createMock(\Magento\Framework\Api\SearchResults::class);
        $searchResultsMock->method('getItems')->willReturn([$this->orderMock]);
        $this->orderRepositoryMock->method('getList')->willReturn($searchResultsMock);
        $this->orderRepositoryMock->method('get')->willReturn($this->orderMock);
        $this->orderRepositoryMock->method('save')->willReturn($this->orderMock);

        // Expect event dispatch
        $this->eventManagerMock->expects($this->once())->method('dispatch')->with(
            'sales_order_save_after',
            ['order' => $this->orderMock, 'old_status' => 'pending']
        );

        // Expect email sender to be called
        $this->orderSenderMock->expects($this->once())->method('send')->with($this->orderMock);

        // Run test
        $response = $this->orderStatusUpdateManagement->postOrderStatusUpdate($orderIncrementId, $orderStatus);

        $this->assertIsArray($response);
        $this->assertEquals(1, $response['response']['order_id']);
        $this->assertEquals($orderStatus, $response['response']['order_status']);
    }

    public function testPostOrderStatusUpdateHandlesExceptions()
    {
        $orderIncrementId = '000000789';
        $orderStatus = 'complete';

        // Mock order repository to throw an exception
        $this->orderRepositoryMock->method('getList')->willThrowException(new \Exception('Some database error'));

        $this->expectException(LocalizedException::class);
        $this->orderStatusUpdateManagement->postOrderStatusUpdate($orderIncrementId, $orderStatus);
    }
}
