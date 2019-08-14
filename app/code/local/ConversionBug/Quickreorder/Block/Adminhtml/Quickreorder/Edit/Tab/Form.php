<?php
class Conversionbug_Quickreorder_Block_Adminhtml_Quickreorder_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("quickreorder_form", array("legend"=>Mage::helper("quickreorder")->__("Item information")));

						$fieldset->addField("order_increment_id", "label", array(
						"label" => Mage::helper("quickreorder")->__("Order Id"),
						"name" => "order_increment_id",
						));
					
						$fieldset->addField("product_id", "label", array(
						"label" => Mage::helper("quickreorder")->__("Product Id"),
						"name" => "product_id",
						));
                        
                        $fieldset->addField("sku", "label", array(
						"label" => Mage::helper("quickreorder")->__("Product SKU"),
						"name" => "sku",
						));
					
						$fieldset->addField("customer_id", "label", array(
						"label" => Mage::helper("quickreorder")->__("Customer Id"),
						"name" => "customer_id",
						));
					
						$fieldset->addField("customer_email", "label", array(
						"label" => Mage::helper("quickreorder")->__("Customer Email"),
						"name" => "customer_email",
						));
					
						$fieldset->addField("period", "label", array(
						"label" => Mage::helper("quickreorder")->__("Period"),
						"name" => "period",
						));
					
						$fieldset->addField("total_period", "label", array(
						"label" => Mage::helper("quickreorder")->__("Total Period"),
						"name" => "total_period",
						));
					
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);
                        
                        $fieldset->addField("replenish_status", "label", array(
						"label" => Mage::helper("quickreorder")->__("Replenish Status"),
						"name" => "replenish_status",
						));

						$fieldset->addField('next_iteration_date', 'label', array(
						'label'        => Mage::helper('quickreorder')->__('Next date'),
						'name'         => 'next_iteration_date',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
                        
						));
                        
                        $fieldset->addField('updated_on', 'label', array(
						'label'        => Mage::helper('quickreorder')->__('Last updated date'),
						'name'         => 'updated_on',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
                        
						));
                        
                        $fieldset->addField('created_time', 'label', array(
						'label'        => Mage::helper('quickreorder')->__('Created date'),
						'name'         => 'created_time',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
                        
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

					

				if (Mage::getSingleton("adminhtml/session")->getQuickreorderData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getQuickreorderData());
					Mage::getSingleton("adminhtml/session")->setQuickreorderData(null);
				} 
				elseif(Mage::registry("quickreorder_data")) {
				    $form->setValues(Mage::registry("quickreorder_data")->getData());
				}
				return parent::_prepareForm();
		}
}
