<?php
	
class Conversionbug_Quickreorder_Block_Adminhtml_Quickreorder_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "quickreorder";
				$this->_controller = "adminhtml_quickreorder";
				$this->_updateButton("save", "label", Mage::helper("quickreorder")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("quickreorder")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("quickreorder")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
                $this->_removeButton('save'); 
                $this->_removeButton('saveandcontinue');
                $this->_removeButton('reset');
                                       
		}

		public function getHeaderText()
		{
				if( Mage::registry("quickreorder_data") && Mage::registry("quickreorder_data")->getId() ){

				    return Mage::helper("quickreorder")->__("View Item #%s", $this->htmlEscape(Mage::registry("quickreorder_data")->getId()));

				} 
				else{

				     return Mage::helper("quickreorder")->__("Add Item");

				}
		}
}