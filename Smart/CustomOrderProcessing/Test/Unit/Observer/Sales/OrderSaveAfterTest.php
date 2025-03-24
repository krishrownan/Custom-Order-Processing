<?php

namespace Smart\CustomOrderProcessing\Test\Unit\Observer\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use PHPUnit\Framework\TestCase;
use Smart\CustomOrderProcessing\Model\OrderStatusLogRepository;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterfaceFactory;
use Smart\CustomOrderProcessing\Api\Data\OrderStatusLogInterface;
use Psr\Log\LoggerInterface;
use Smart\CustomOrderProcessing\Observer\Sales\OrderSaveAfter;
use Magento\Framework\Exception\LocalizedException;

class OrderSaveAfterTest extends TestCase
{
    private $orderStatusLogRepositoryMock;
    private $orderStatusLogFactoryMock;
    private $loggerMock;
    private $observerMock;
    private $eventMock;
    private $orderMock;
    private $orderStatusLogMock;
    private $orderSaveAfter;

    protected function setUp(): void
    {
        $this->orderStatusLogRepositoryMock = $this->createMock(OrderStatusLogRepository::class);
        $this->orderStatusLogFactoryMock = $this->createMock(OrderStatusLogInterfaceFactory::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->observerMock = $this->createMock(Observer::class);
        $this->eventMock = $this->createMock(Observer::class);
        $this->orderMock = $this->createMock(Order::class);
        $this->orderStatusLogMock = $this->createMock(OrderStatusLogInterface::class);

        $this->orderSaveAfter = new OrderSaveAfter(
            $this->orderStatusLogRepositoryMock,
            $this->orderStatusLogFactoryMock,
            $this->loggerMock
        );
    }

    public function testExecuteSuccess()
    {
        $orderId = 123;
        $oldStatus = 'pending';
        $newStatus = 'processing';

        $this->orderMock->method('getId')->willReturn($orderId);
        $this->orderMock->method('getStatus')->willReturn($newStatus);

        $this->observerMock->method('getEvent')->willReturnSelf();
        $this->observerMock->method('getOrder')->willReturn($this->orderMock);
        $this->observerMock->method('getOldStatus')->willReturn($oldStatus);

        $this->orderStatusLogFactoryMock->method('create')->willReturn($this->orderStatusLogMock);

        $this->orderStatusLogMock->expects($this->once())->method('setOrderId')->with($orderId);
        $this->orderStatusLogMock->expects($this->once())->method('setOldStatus')->with($oldStatus);
        $this->orderStatusLogMock->expects($this->once())->method('setNewStatus')->with($newStatus);
        $this->orderStatusLogMock->expects($this->once())->method('setCreatedAt');

        $this->orderStatusLogRepositoryMock->expects($this->once())->method('save')->with($this->orderStatusLogMock);
        $this->loggerMock->expects($this->once())->method('info')->with("Order status logged successfully for ID: $orderId");

        $this->orderSaveAfter->execute($this->observerMock);
    }

    public function testExecuteThrowsException()
    {
        $orderId = 123;
        $oldStatus = 'pending';
        $newStatus = 'processing';

        $this->orderMock->method('getId')->willReturn($orderId);
        $this->orderMock->method('getStatus')->willReturn($newStatus);
        $this->observerMock->method('getEvent')->willReturnSelf();
        $this->observerMock->method('getOrder')->willReturn($this->orderMock);
        $this->observerMock->method('getOldStatus')->willReturn($oldStatus);

        $this->orderStatusLogFactoryMock->method('create')->willReturn($this->orderStatusLogMock);

        $this->orderStatusLogRepositoryMock->method('save')->willThrowException(new \Exception('Database error'));
        
        $this->loggerMock->expects($this->once())->method('error')->with("Error logging order ID: Database error");

        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage("Error logging order status: Database error");

        $this->orderSaveAfter->execute($this->observerMock);
    }
}
