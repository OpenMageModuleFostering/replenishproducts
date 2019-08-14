<?php

/*
 * ConversionBug_Core
 *
 * @category     ConversionBug
 * @package      ConversionBug_Core
 * @copyright    Copyright (c) 2016 ConversionBug (http://www.conversionbug.com/)
 * @author       Ramesh Allamsetti
 * @email        ramesh.allamsetti@convertifier.com
 * @version      Release: 0.3.0
 */

class ConversionBug_Core_Model_System_Config_Source_Footer
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(

            array('value' => 1, 'label' => Mage::helper('adminhtml')->__('1')),
            array('value' => 2, 'label' => Mage::helper('adminhtml')->__('2')),
            array('value' => 3, 'label' => Mage::helper('adminhtml')->__('3')),
            array('value' => 4, 'label' => Mage::helper('adminhtml')->__('4')),
            array('value' => 5, 'label' => Mage::helper('adminhtml')->__('5')),
            array('value' => 6, 'label' => Mage::helper('adminhtml')->__('6')),
        );
    }
}