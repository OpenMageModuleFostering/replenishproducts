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

class ConversionBug_Quickreorder_Block_Adminhtml_Quickreorder extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

    	$this->_controller = "adminhtml_quickreorder";
    	$this->_blockGroup = "quickreorder";
    	$this->_headerText = Mage::helper("quickreorder")->__("Manage Replenish Items");
    	$this->_addButtonLabel = Mage::helper("quickreorder")->__("Add New Item");
    	parent::__construct();
    	$this->_removeButton('add');
    
	}

}