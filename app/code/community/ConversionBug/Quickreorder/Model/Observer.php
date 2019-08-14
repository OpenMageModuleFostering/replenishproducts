<?php

/*
 * ConversionBug_Quickreorder
 *
 * @category     ConversionBug
 * @package      ConversionBug_Quickreorder
 * @copyright    Copyright (c) 2016 ConversionBug (http://www.conversionbug.com/)
 * @author       Ramesh Allamsetti
 * @email        ramesh.allamsetti@conversionbug.com
 * @version      Release: 1.2.0
 */

class ConversionBug_Quickreorder_Model_Observer
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

    public function rePlenishEmail()
    {
        Mage::unregister('_singleton/core/design_package' );
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
        Mage::getSingleton('core/design_package' )->setStore(1);

        $todaydate = time();
        $tomorrowdate = time()+86400;

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

        foreach($repleshProducts as $repleshProduct){


            $productData = Mage::getModel("catalog/product")->load($repleshProduct->getProductId());
            $customerData = Mage::getModel('customer/customer')->load($repleshProduct->getCustomerId());
            $store = Mage::app()->getStore();
            $emailTemplate  = Mage::getModel('core/email_template')->loadDefault('replenishmail_notification');
            
            $producthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/product.phtml')->toHtml();
                                
            $relatedproducthtml = Mage::app()->getLayout()->createBlock('quickreorder/product')
            					->setData('product', $productData)
            					->setTemplate('reorder/related_products.phtml')->toHtml();                                

            $emailTemplateVariables = array();
            $emailTemplateVariables['product'] = $producthtml;
            $emailTemplateVariables['customer'] = $customerData;

            $cname = $customerData->getFirstname();
            $cname = ucwords($cname);
            $cname = ucwords(strtolower($cname));
            $emailTemplateVariables['customer_name'] = $cname;
			
            $emailTemplateVariables['store'] = $store;
            $emailTemplateVariables['related_products'] = $relatedproducthtml;

            $processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
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
                
            }catch(Exception $error){
                Mage::log($error->getMessage(),null,'quickorder-expection.log');
            }

            $nextmailsendtime = $this->getnextmailtime($repleshProduct);
            $last_iteration_date = $this->getlastmailtime($repleshProduct);
            $updated_date = date("Y-m-d h:i:sa", time());

            if($nextmailsendtime < $last_iteration_date){
                $repleshProduct->setNextIterationDate($nextmailsendtime);
                $repleshProduct->setUpdatedOn($updated_date);
                $repleshProduct->setReplenishStatus(1);
                $repleshProduct->save();
            }else{
                $adjusttime = $this->getadjustmailtime($repleshProduct);
                $repleshProduct->setNextIterationDate($adjusttime);
                $repleshProduct->setUpdatedOn($updated_date);
                $repleshProduct->setReplenishStatus(1);
                $repleshProduct->save();
            }
          }
        }

    
    public function getnextmailtime($repleshProduct){
        $replenishdata = $repleshProduct->getData();
        $currenttime = strtotime($replenishdata['next_iteration_date']);
        $next_iteration_date = $currenttime + (86400 * $replenishdata['period']);
        $next_iteration_date = date("Y-m-d h:i:sa", $next_iteration_date); 
        return $next_iteration_date;
    }

    public function getadjustmailtime($repleshProduct){
        $replenishdata = $repleshProduct->getData();
        $currenttime = time();
        $next_iteration_date =  $currenttime + (86400 * $replenishdata['total_period']) * 30;
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
