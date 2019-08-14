<?php
class Conversionbug_Quickreorder_Model_Observer
{
	public function orderLog(Varien_Event_Observer $observer)
	{
	    $moduleEnabled = Mage::Helper('quickreorder')->isEnabled();
        if($moduleEnabled){
            $orderIds = $observer->getEvent()->getOrderIds();
            foreach($orderIds as $orderid){
                $replenishData  = Mage::Helper('quickreorder')->saveReplenishData($orderid);
            }
            return $this;
        }
	}
    
    public function viewProduct(Varien_Event_Observer $observer) {
        $moduleEnabled = Mage::Helper('quickreorder')->isEnabled();
        if($moduleEnabled){
            /*$orders = array(201);
            foreach($orders as $orderid){
                $replenishData  = Mage::Helper('quickreorder')->saveReplenishData($orderid);
            } */
        }
         
        return;
    }
    public function rePlenishTestEmail()
    {
        
        //return true;
        //http://inchoo.net/magento/magento-custom-emails/
        $todaydate = time();
        $tomorrowdate = time()+86400;
        /*$todaydate = time() + (86400 * 20);
        $tomorrowdate = time() + (86400 * 31);*/
        //echo date('Y-m-d 00:00:00', $todaydate)."--".date('Y-m-d 00:00:00', $tomorrowdate);die;  
        $repleshProducts = Mage::getModel("quickreorder/quickreorder")
                                ->getCollection()
                                ->addfieldtofilter('next_iteration_date', 
                                    array(
                                        array('to' => date('Y-m-d 00:00:00', $tomorrowdate))
                                    )
                                )
                                ->addfieldtofilter('next_iteration_date', 
                                    array(
                                        array('gteq' => date('Y-m-d 00:00:00', $todaydate))
                                    )
                                );
        $sql = $repleshProducts->getSelect();
        Mage::log($sql, null, 'reorder_test.log');
        //echo $sql;die;
        //echo "<pre>"; print_R($repleshProducts->getData());die;
        foreach($repleshProducts as $repleshProduct){
            
            $productData = Mage::getModel("catalog/product")->load($repleshProduct->getProductId());
            $customerData = Mage::getModel('customer/customer')->load($repleshProduct->getCustomerId());
            $store = Mage::app()->getStore();
            //if($customerData->getEmail()=="shivam.kumar@conversionbug.com"){
            //echo "<pre>"; print_R($customerData->getData());die;
            $emailTemplate  = Mage::getModel('core/email_template')->loadDefault('replenishmail_notification');
            
            $producthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/product.phtml')->toHtml();
                                
            $relatedproducthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/related_products.phtml')->toHtml();                                
           
           //echo $producthtml;die;
            //echo $relatedproducthtml;die;
            //Variables for Confirmation Mail.
            $emailTemplateVariables = array();
            $emailTemplateVariables['product'] = $producthtml;
            Mage::log($producthtml,null,'reorder_test.log');
            $emailTemplateVariables['customer'] = $customerData;

            $cname = $customerData->getFirstname();
            $cname = ucwords($cname);             // HELLO WORLD!
            $cname = ucwords(strtolower($cname));
            $emailTemplateVariables['customer_name'] = $cname;
            Mage::log($cname,null,'reorder_test.log');
            $emailTemplateVariables['store'] = $store;
            //$emailTemplateVariables['related_products'] = $relatedproducthtml;
            Mage::log($relatedproducthtml,null,'reorder_test.log');
            //die("test sent");
            //Appending the Custom Variables to Template.
            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
           
            //Sender details
            $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
            $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
            //recepient details
            $recepientEmail = $customerData->getEmail();
            $recepientName = $customerData->getFirstname();        
            Mage::log($recepientEmail,null,'reorder_test.log');
            $mail = Mage::getModel('core/email')
                        ->setToName($recepientName)
                        ->setToEmail($recepientEmail)
                        ->setBody($processedTemplate)
                        ->setSubject('Now It\'s  time to re-order - '.$productData->getName())
                        ->setFromEmail($senderEmail)
                        ->setFromName($senderName)
                        ->setType('html');
            try{
                //Confimation E-Mail Send
                $mail->send();
                 //echo "<br><br>"; print_R($processedTemplate);die;
               // echo $processedTemplate;exit;
            }catch(Exception $error){
                //echo $error->getMessage();die;
                
            }
            //echo "<pre>"; print_R($emailTemplate->getData());die;
            $nextmailsendtime = $this->getnextmailtime($repleshProduct);
            $last_iteration_date = $this->getlastmailtime($repleshProduct);
            $updated_date = date("Y-m-d h:i:sa", time()); 
            if($nextmailsendtime < $last_iteration_date){
                $repleshProduct->setNextIterationDate($nextmailsendtime);
                $repleshProduct->setUpdatedOn($updated_date);
                $repleshProduct->setReplenishStatus(0);
                $repleshProduct->save();
            }else{
                $repleshProduct->delete();
            }
        //}
        }

        //return $this;
    }
    public function rePlenishEmail()
    {
        Mage::unregister('_singleton/core/design_package' );
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        Mage::getSingleton('core/design_package' )->setStore(1);
        
        //return true;
        //http://inchoo.net/magento/magento-custom-emails/
        $todaydate = time();
        $tomorrowdate = time()+86400;
        /*$todaydate = time() + (86400 * 20);
        $tomorrowdate = time() + (86400 * 31);*/
        //echo date('Y-m-d 00:00:00', $todaydate)."--".date('Y-m-d 00:00:00', $tomorrowdate);die;  
        $repleshProducts = Mage::getModel("quickreorder/quickreorder")
                                ->getCollection()
                                ->addfieldtofilter('next_iteration_date', 
                                    array(
                                        array('to' => date('Y-m-d 00:00:00', $tomorrowdate))
                                    )
                                )
                                ->addfieldtofilter('next_iteration_date', 
                                    array(
                                        array('gteq' => date('Y-m-d 00:00:00', $todaydate))
                                    )
                                );
        $sql = $repleshProducts->getSelect();
        Mage::log($sql, null, 'reorder.log');
        //echo $sql;die;
        //echo "<pre>"; print_R($repleshProducts->getData());die;
        foreach($repleshProducts as $repleshProduct){
            
            $productData = Mage::getModel("catalog/product")->load($repleshProduct->getProductId());
            $customerData = Mage::getModel('customer/customer')->load($repleshProduct->getCustomerId());
            $store = Mage::app()->getStore();
            if($customerData->getEmail()!="kannan_jayaprakasam@yahoo.co.in"){
            //echo "<pre>"; print_R($customerData->getData());die;
            $emailTemplate  = Mage::getModel('core/email_template')->loadDefault('replenishmail_notification');
            
            $producthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/product.phtml')->toHtml();
                                
            $relatedproducthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/related_products.phtml')->toHtml();                                
           
           //echo $producthtml;die;
            //echo $relatedproducthtml;die;
            //Variables for Confirmation Mail.
            $emailTemplateVariables = array();
            $emailTemplateVariables['product'] = $producthtml;
            $emailTemplateVariables['customer'] = $customerData;

            $cname = $customerData->getFirstname();
            $cname = ucwords($cname);             // HELLO WORLD!
            $cname = ucwords(strtolower($cname));
            $emailTemplateVariables['customer_name'] = $cname;
			
            $emailTemplateVariables['store'] = $store;
            $emailTemplateVariables['related_products'] = $relatedproducthtml;
            //die("test sent");
            //Appending the Custom Variables to Template.
            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
           //echo $processedTemplate;exit;
            //Sender details
            $senderName = Mage::getStoreConfig('trans_email/ident_support/name');
            $senderEmail = Mage::getStoreConfig('trans_email/ident_support/email');
            //recepient details
            $recepientEmail = $customerData->getEmail();
            $recepientName = $customerData->getFirstname();        
            
            $mail = Mage::getModel('core/email')
                        ->setToName($recepientName)
                        ->setToEmail($recepientEmail)
                        ->setBody($processedTemplate)
                        ->setSubject('Now It\'s  time to re-order - '.$productData->getName())
                        ->setFromEmail($senderEmail)
                        ->setFromName($senderName)
                        ->setType('html');
            try{
                //Confimation E-Mail Send
                $mail->send();
                 //echo "<br><br>"; print_R($processedTemplate);die;
                
            }catch(Exception $error){
                //echo $error->getMessage();die;
                
            }
            //echo "<pre>"; print_R($emailTemplate->getData());die;
            $nextmailsendtime = $this->getnextmailtime($repleshProduct);
            $last_iteration_date = $this->getlastmailtime($repleshProduct);
            $updated_date = date("Y-m-d h:i:sa", time()); 
            if($nextmailsendtime < $last_iteration_date){
                $repleshProduct->setNextIterationDate($nextmailsendtime);
                $repleshProduct->setUpdatedOn($updated_date);
                $repleshProduct->setReplenishStatus(0);
                $repleshProduct->save();
            }else{
                $repleshProduct->delete();
            }
        }
        }

        //return $this;
    }
    
    public function getnextmailtime($repleshProduct){
        $replenishdata = $repleshProduct->getData();
        $currenttime = strtotime($replenishdata['next_iteration_date']);
        $next_iteration_date = $currenttime + (86400 * $replenishdata['period']);
        $next_iteration_date = date("Y-m-d h:i:sa", $next_iteration_date); 
        return $next_iteration_date;
    }
    
    public function getlastmailtime($repleshProduct){
        $replenishdata = $repleshProduct->getData();
        $currenttime = strtotime($replenishdata['created_time']);
        $last_iteration_date = $currenttime + (86400 * $replenishdata['total_period']) * 30;
        $last_iteration_date = date("Y-m-d h:i:sa", $last_iteration_date); 
        return $last_iteration_date;
    }
    
    public function deleteProduct(Varien_Event_Observer $observer){
        $productId = $observer->getEvent()->getProduct()->getId();
        $repleshProducts = Mage::getModel("quickreorder/quickreorder")->getCollection()->addfieldtofilter('product_id',$productId);
        foreach($repleshProducts as $repleshProduct){
            $repleshProduct->delete();
        }
        return true;
    }
    
    public function deleteCustomer(Varien_Event_Observer $observer){
        $customerId = $observer->getEvent()->getCustomer()->getId();
        $repleshProducts = Mage::getModel("quickreorder/quickreorder")->getCollection()->addfieldtofilter('customer_id',$customerId);
        foreach($repleshProducts as $repleshProduct){
            $repleshProduct->delete();
        }
        return true;
    }
    
}
