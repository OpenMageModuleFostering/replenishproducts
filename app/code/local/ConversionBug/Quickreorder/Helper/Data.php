<?php
class Conversionbug_Quickreorder_Helper_Data extends Mage_Core_Helper_Abstract
{
    const REORDER_ENABLED = 'quickreorder/general/status';
	
    public function isEnabled()
    {
        return Mage::getStoreConfig(self::REORDER_ENABLED);
    }
	
    public function saveReplenishData($orderid){
    
        $order = Mage::getModel('sales/order')->load($orderid);
        if (!$order->getId()) {
            return;
        }
        $orderData = $order->getData();
        $replenishData = array();
        //order info
        $replenishData['order_id'] = $orderData['entity_id'];
        $replenishData['order_increment_id'] = $orderData['increment_id'];
        //customer data
        $replenishData = $this->PreparedataCustomer($orderData,$replenishData);
        //product data
        $ordered_items = $order->getAllItems();
    
	    foreach($ordered_items as $ordered_item){
            $product = Mage::getModel('catalog/product')->load($ordered_item->getProductId());            
            $recurringstatus = $product->getRecurringStatus();
            $productid = $product->getId();
            //echo $replenishData['customer_email']."--".$productid;
            
			if($this->checkRecord($replenishData['customer_email'],$productid)){
                
				if($recurringstatus == 1){
                    $replenishData['product_id'] = $product->getId();
                    $replenishData['period'] = $product->getRecurringTimespan();
                    $replenishData['total_period'] = $product->getRecurringTotalPeriod();
                    //$nextdate = time() + (86400 * $product->getRecurringTimespan());
                    $nextdate = time() + (60 * $product->getRecurringTimespan());
                    $nextdate = date("Y-m-d h:i:sa", $nextdate);
                    $replenishData['next_iteration_date'] = $nextdate;
                    $model = Mage::getModel("quickreorder/quickreorder")
    						->addData($replenishData)
    						->save();
                }
            }
            unset($replenishData['product_id']);unset($replenishData['period']);unset($replenishData['total_period']);
        }
        return true;
    }
    
    public function PreparedataCustomer($orderData,$replenishData){
        $replenishData['customer_id'] = $orderData['customer_id'];
        $replenishData['customer_email'] = $orderData['customer_email'];
        return $replenishData;
    }
    
    public function checkRecord($customer_email,$product_id){
        $read = Mage::getSingleton('core/resource')->getConnection('core_read'); 
        $qry = "select * FROM conversion_quickreorder WHERE customer_email = '{$customer_email}' and product_id = {$product_id}"; //query            
        $res = $read->fetchRow($qry);
        
        if($res){
            //$repleshProduct->setReplenishStatus(0);
            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
     
            $query = "UPDATE conversion_quickreorder SET replenish_status = '1'  WHERE customer_email = '{$customer_email}' and product_id = {$product_id}"; //query
            $writeConnection->query($query);
            return false;
        }
        return true;
    }
}
	 