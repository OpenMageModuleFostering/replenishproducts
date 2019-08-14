<?php
$installer = $this;
$installer->startSetup();

$installer->run("
CREATE TABLE IF NOT EXISTS {$this->getTable('conversion_quickreorder')} (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_increment_id` varchar(256) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_email` varchar(256) DEFAULT NULL,
  `period`  int(11) NOT NULL,
  `total_period`  int(11) NOT NULL,
  `replenish_status` tinyint(1) NOT NULL,
  `next_iteration_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');

/**
 * Adding Different Attributes
 */
 
// adding attribute group
$setup->addAttributeGroup('catalog_product', 'Default', 'Replenish Attributes', 1000);
 
// the attribute added will be displayed under the group/tab Special Attributes in product edit page

$setup->addAttribute('catalog_product', "recurring_status", array(
    'group'     	=> 'Replenish Attributes',
    'type'              => 'int',//can be int, varchar, decimal, text, datetime
    'backend'           => '',
    'frontend_input'    => '',
    'frontend'          => '',
    'label'             => 'Status',
    'input'             => 'select', //text, textarea, select, file, image, multilselect
    'default' => array(0),
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',//this is necessary for select and multilelect, for the rest leave it blank
    'global'             => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,//scope can be SCOPE_STORE or SCOPE_GLOBAL or SCOPE_WEBSITE
    'visible'           => true,
    'frontend_class'     => '',
    'required'          => false,//or true
    'user_defined'      => true,
    'default'           => '',
    'position'            => 0,//any number will do
));

$setup->addAttribute('catalog_product', 'recurring_timespan', array(
	'group'     	=> 'Replenish Attributes',
	'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Replenish Timespan',
	'backend'       => '',
	'visible'       => 1,
	'required'		=> 0,
	'user_defined' => 1,
	'searchable' => 1,
	'filterable' => 0,
	'comparable'	=> 1,
	'visible_on_front' => 1,
	'visible_in_advanced_search'  => 0,
	'is_html_allowed_on_front' => 0,
    'note' => 'In Days',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
 
$setup->addAttribute('catalog_product', 'recurring_total_period', array(
	'group'     	=> 'Replenish Attributes',
	'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Replenish Total Period',
	'backend'       => '',
	'visible'       => 1,
	'required'		=> 0,
	'user_defined' => 1,
	'searchable' => 1,
	'filterable' => 0,
	'comparable'	=> 1,
	'visible_on_front' => 1,
	'visible_in_advanced_search'  => 0,
	'is_html_allowed_on_front' => 0,
    'note' => 'In Months',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));
 
$installer->endSetup();
	 