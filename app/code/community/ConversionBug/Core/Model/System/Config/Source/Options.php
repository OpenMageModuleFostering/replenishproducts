<?php

/*
 * ConversionBug_Core
 *
 * @category     ConversionBug
 * @package      ConversionBug_Core
 * @copyright    Copyright (c) 2016 ConversionBug (http://www.conversionbug.com/)
 * @author       Ramesh Allamsetti
 * @email        ramesh.allamsetti@conversionbug.com
 * @version      Release: 1.2.0
 */

class ConversionBug_Core_Model_System_Config_Source_Options
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 1, 'label'=>Mage::helper('adminhtml')->__('Yes')),
            array('value' => 0, 'label'=>Mage::helper('adminhtml')->__('No')),
        );
    }

}
