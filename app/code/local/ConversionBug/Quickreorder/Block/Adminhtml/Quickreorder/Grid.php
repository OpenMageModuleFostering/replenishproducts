<?php

class Conversionbug_Quickreorder_Block_Adminhtml_Quickreorder_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("quickreorderGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("quickreorder/quickreorder")->getCollection();
                $collection->getSelect()->join(Mage::getConfig()->getTablePrefix().'catalog_product_entity', 'main_table.product_id ='.Mage::getConfig()->getTablePrefix().'catalog_product_entity.entity_id',array('sku'));
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("quickreorder")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "id",
				));
				
				$this->addColumn("order_increment_id", array(
    				"header" => Mage::helper("quickreorder")->__("Order Id"),
    				"index" => "order_increment_id",
				));
				$this->addColumn("sku", array(
    				"header" => Mage::helper("quickreorder")->__("Product Sku"),
    				"index" => "sku",
				));

				$this->addColumn("customer_email", array(
				"header" => Mage::helper("quickreorder")->__("Customer Email"),
				"index" => "customer_email",
				));
				$this->addColumn("period", array(
				"header" => Mage::helper("quickreorder")->__("Period"),
				"index" => "period",
				));
				$this->addColumn("total_period", array(
				"header" => Mage::helper("quickreorder")->__("Total Period"),
				"index" => "total_period",
				));
					$this->addColumn('next_iteration_date', array(
						'header'    => Mage::helper('quickreorder')->__('Next date'),
						'index'     => 'next_iteration_date',
						'type'      => 'datetime',
					));
					$this->addColumn('created_time', array(
						'header'    => Mage::helper('quickreorder')->__('Created at'),
						'index'     => 'created_time',
						'type'      => 'datetime',
					));
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_quickreorder', array(
					 'label'=> Mage::helper('quickreorder')->__('Remove'),
					 'url'  => $this->getUrl('*/adminhtml_quickreorder/massRemove'),
					 'confirm' => Mage::helper('quickreorder')->__('Are you sure?')
				));
			return $this;
		}
			

}