<?php
class Conversionbug_Quickreorder_Model_Mysql4_Quickreorder extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("quickreorder/quickreorder", "id");
    }
}