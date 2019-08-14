<?php

class Conversionbug_Quickreorder_Adminhtml_QuickreorderController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
			$this->loadLayout()->_setActiveMenu("quickreorder/quickreorder")->_addBreadcrumb(Mage::helper("adminhtml")->__("Quickreorder  Manager"),Mage::helper("adminhtml")->__("Manage Replenish Items"));
			return $this;
		}
		public function indexAction() 
		{
		    $this->_title($this->__("Quickreorder"));
		    $this->_title($this->__("Manage Replenish Items"));

			$this->_initAction();
			$this->renderLayout();
		}
		public function editAction()
		{			    
		    $this->_title($this->__("Quickreorder"));
			$this->_title($this->__("Quickreorder"));
		    $this->_title($this->__("View Item"));
			
			$id = $this->getRequest()->getParam("id");
			$model = Mage::getModel("quickreorder/quickreorder")->load($id);
            $sku = Mage::getResourceModel('catalog/product')->getAttributeRawValue($model->getProductId(), 'sku', 1);
            $model->setSku($sku);
            $statuschange = ($model->getReplenishStatus() == 0) ? "Not yet Purchased" : "Purchased"; 
            $model->setReplenishStatus($statuschange);
			if ($model->getId()) {
				Mage::register("quickreorder_data", $model);
				$this->loadLayout();
				$this->_setActiveMenu("quickreorder/quickreorder");
				$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manage Replenish Items"), Mage::helper("adminhtml")->__("Manage Replenish Items"));
				$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Quickreorder Description"), Mage::helper("adminhtml")->__("Quickreorder Description"));
				$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
				$this->_addContent($this->getLayout()->createBlock("quickreorder/adminhtml_quickreorder_edit"))->_addLeft($this->getLayout()->createBlock("quickreorder/adminhtml_quickreorder_edit_tabs"));
				$this->renderLayout();
			} 
			else {
				Mage::getSingleton("adminhtml/session")->addError(Mage::helper("quickreorder")->__("Item does not exist."));
				$this->_redirect("*/*/");
			}
		}

		public function newAction()
		{

    		$this->_title($this->__("Quickreorder"));
    		$this->_title($this->__("Quickreorder"));
    		$this->_title($this->__("New Item"));
    
            $id   = $this->getRequest()->getParam("id");
    		$model  = Mage::getModel("quickreorder/quickreorder")->load($id);
    
    		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
    		if (!empty($data)) {
    			$model->setData($data);
    		}
    
    		Mage::register("quickreorder_data", $model);
    
    		$this->loadLayout();
    		$this->_setActiveMenu("quickreorder/quickreorder");
    
    		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
    
    		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Manage Replenish Items"), Mage::helper("adminhtml")->__("Manage Replenish Items"));
    		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Quickreorder Description"), Mage::helper("adminhtml")->__("Quickreorder Description"));
    
    
    		$this->_addContent($this->getLayout()->createBlock("quickreorder/adminhtml_quickreorder_edit"))->_addLeft($this->getLayout()->createBlock("quickreorder/adminhtml_quickreorder_edit_tabs"));
    
    		$this->renderLayout();

		}
		public function saveAction()
		{
			$post_data=$this->getRequest()->getPost();
			if ($post_data) {

				try {
					$model = Mage::getModel("quickreorder/quickreorder")
        						->addData($post_data)
        						->setId($this->getRequest()->getParam("id"))
        						->save();

					Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Quickreorder was successfully saved"));
					Mage::getSingleton("adminhtml/session")->setQuickreorderData(false);

					if ($this->getRequest()->getParam("back")) {
						$this->_redirect("*/*/edit", array("id" => $model->getId()));
						return;
					}
					$this->_redirect("*/*/");
					return;
				} 
				catch (Exception $e) {
					Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
					Mage::getSingleton("adminhtml/session")->setQuickreorderData($this->getRequest()->getPost());
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				return;
				}

			}
			$this->_redirect("*/*/");
		}

		public function deleteAction()
		{
			if( $this->getRequest()->getParam("id") > 0 ) {
				try {
					$model = Mage::getModel("quickreorder/quickreorder");
					$model->setId($this->getRequest()->getParam("id"))->delete();
					Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
					$this->_redirect("*/*/");
				} 
				catch (Exception $e) {
					Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
					$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
				}
			}
			$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("quickreorder/quickreorder");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'quickreorder.csv';
			$grid       = $this->getLayout()->createBlock('quickreorder/adminhtml_quickreorder_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'quickreorder.xml';
			$grid       = $this->getLayout()->createBlock('quickreorder/adminhtml_quickreorder_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
