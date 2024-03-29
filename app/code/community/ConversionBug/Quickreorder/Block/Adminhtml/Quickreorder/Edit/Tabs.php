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

class ConversionBug_Quickreorder_Block_Adminhtml_Quickreorder_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("quickreorder_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("quickreorder")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("quickreorder")->__("Item Information"),
				"title" => Mage::helper("quickreorder")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("quickreorder/adminhtml_quickreorder_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
