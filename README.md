Follow the below steps to work this module properly,

Step 1 : Clone or download the module and place it under **Magento_Root_dir/app/code/Smart/CustomOrderProcessing**

Step 2 : Enable the module by running bin/magento module:enable in magento root directory and run the deployment comments like **bin/magento setup:upgrade && bin/magento setup:di:compile && bin/magento setup:static-content:deploy -f**

Step 3 : Now take the existing magento order increment id from Magento admin Sales -> Order section and open the postman and use this REST API end point **<Magento_base_url>/rest/all/V1/customer/orderstatusupdate**, please refer below attachment.
![order_update_status](https://github.com/user-attachments/assets/9798956f-0e96-416f-8149-4c7943dea94d)

        
Step 4: Before hitting the request please make sure you have valid authentication token set in the auth section please refer below attachment.
        ![with_auth](https://github.com/user-attachments/assets/f10d3b86-a99d-4fef-99c1-cf24d12bb3da)

        It through error if no Auth token provided.
        
        ![image](https://github.com/user-attachments/assets/8ab39449-d0e8-4bad-867d-fc67e272ffca)


Step 5: To generate Authentication token use this REST API end point **<Magento_base_url>/rest/all/V1/integration/admin/token** and pass the payload of your Magento admin user name and password
       ![image](https://github.com/user-attachments/assets/d709d6f9-721e-44d5-8d5e-3be0ca12f7c0)

Step 6: Now all set and hit the REST API Request it will shown like below,
      ![order_update_status](https://github.com/user-attachments/assets/ab50d092-3fc4-4b5d-8f9c-7cdba8f6e770)

      Below attachment shows the before hiting the request the order status shown in Magento admin

      ![original_status](https://github.com/user-attachments/assets/25279a3f-8530-4fba-8f1f-81de1e816cb3)

      After hiting the custom REST API the admin order status changed like below screen,
      ![Updated_status](https://github.com/user-attachments/assets/0c10b937-b3aa-4d61-841c-cea6745582a8)

Step 7 : The observer also got triggered whenever status got updated we can see the entries under **order_status_update_log**
         ![image](https://github.com/user-attachments/assets/8875fa90-151e-4fd2-95bf-9545d1427613)






