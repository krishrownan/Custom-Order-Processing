**Custom Order Processing:**

The purpose of this module is to securely update customer order statuses using a REST API, while monitoring and storing status changes in a custom table.

**Feature:**

1. Securely update order status via REST API.

    ● Ensures only authorized users or systems can update order statuses.

3. Improve performance using caching mechanisms.
   
    ● Implements the Repository pattern, MySQL indexing, and CacheInterface for efficient data retrieval.
   
4. Track order status logs through the Admin UI.

    ● Provides a user-friendly interface for admins to monitor the full history of order status changes.

5. Robust error handling and validation.

    ● Validates input data and gracefully handles exceptions to ensure stability.

6. Custom status mapping and configuration.
   
    ● Allows configuration of custom order status mappings from the backend or a config file.

7. Audit trail and logging.
    
    ● Maintains a detailed audit trail for compliance and debugging purposes.
   
8. Extensibility and integration support.

    ● Designed to be easily extendable for integration with external systems (e.g., ERP, OMS).
   
9. Unit and Integration Testing implemented
   
   ● Ensures code quality, reliability, and proper functionality through automated testing.
   
**Setup And Installation:**

Please follow the below steps to install modules in Magento 2 store,

1. Download the module as **ZIP** or run the **git clone** https://github.com/krishrownan/Custom-Order-Processing.git in your system.

2. Place the downloaded module under **Magento_Root_Dir/app/code/<put here>**  (i.e) Magento_Root_Dir/app/code/Smart/*

3. Run the below commands from SSH Magento root directory
   **bin/magento setup:upgrade**
   **bin/magento setup:di:compile**
   **bin/magento setup:static-content:deploy -f**

4. Now check whether module is installed or not by running, 
   **bin/magento module:status Smart_CustomOrderProcessing**
   
5.We can see below screen if module installed successfully by running above commands,

   ![image](https://github.com/user-attachments/assets/06006551-c5dd-46f6-8e4d-149f3a05d497)
   

 **WorkFlow:**
1. Generate Access token from Magento admin under System -> Integration
2. Open Postman and set this end point with **POST** request **https://your-magento-domain.com/rest/all/V1/customer/orderstatusupdate** please refer below attachment for reference and set the payload
3. Set the payload please refer below attachment for refererence and hit the request.We can able to see the response if order status updated.
   **Payload:**
   {
     "orderIncrementId": "000000004",
     "orderStatus": "complete" 
   }

   ![order_update_status](https://github.com/user-attachments/assets/a5ec13ed-7053-47e2-bfc1-420313c74d9e)

4. If there is any issue while updaing the status it through an error i.e (Invalid Access token or incorrect payload)
      ![validation](https://github.com/user-attachments/assets/8d841cd0-3950-4210-8b63-d9683d62fa88)
   
5. Whenever order status changes the changes are inserted in **custom order_status_change_log** table

   ![DB_update](https://github.com/user-attachments/assets/1e59e78b-a9c7-4f12-8a23-ff44572dbe6e)

6.Navigate to Magento admin and go to Smart like below,

   ![image](https://github.com/user-attachments/assets/77c3b241-5b4f-4dfb-9ce5-9ccbd44f743f)

7.Implemented Admin UI with CRUD operation

![image](https://github.com/user-attachments/assets/a0e0824f-e40c-4e83-821d-b85d51803b30)


