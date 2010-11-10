<?php

/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_LiveUser_Tab_Tracking
 *
 * NOTICE OF LICENSE
 * 
 * Faett_Piwik is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Faett_Piwik is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Faett_Piwik.  If not, see <http://www.gnu.org/licenses/>.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Faett_Piwik to newer
 * versions in the future. If you wish to customize Faett_Piwik for your
 * needs please refer to http://www.faett.net for more information.
 *
 * @category    Faett
 * @package     Faett_Piwik
 * @copyright   Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license     <http://www.gnu.org/licenses/> 
 * 			    GNU General Public License (GPL 3)
 */

/**
 * Adminhtml dashboard live user grid
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Block_Adminhtml_Dashboard_LiveUser_Tab_Tracking 
	extends Mage_Adminhtml_Block_Dashboard_Grid {

	/**
	 * Initializes the grid.
	 * 
	 * @return void
	 */
    public function __construct()
    {
        parent::__construct();
        $this->setId('liveUserGrid');
    }

    /**
     * Loads the necessary live user data
     * from the specified Piwik instance.
     * 
     * @return Faett_Piwik_Block_Adminhtml_Dashboard_Tab_Live_User The instance
     */
    protected function _prepareCollection()
    {
    	// initialize the Piwik service
    	$service = Faett_Piwik_Service::create();
    	// load the live user data
		$collection = $service->call('Live.getLastVisits');
        // set the data
		$this->setCollection($collection);
		// call the method of the parent class
        return parent::_prepareCollection();
    }

    /**
	 * Prepares the columns and specifies
	 * the templates to use.
	 * 
	 * @return Faett_Piwik_Block_Adminhtml_Dashboard_Tab_Live_User The instance
     */
    protected function _prepareColumns()
    {
        // add a column for the visitor data
        $this->addColumn('visitor', array(
            'header'    => Mage::helper('piwik')->__('Visitor'),
            'sortable'  => false,
            'renderer'  => 'piwik/adminhtml_report_grid_column_renderer_generic',
            'tmpl'  	=> 'piwik/report/grid/column/renderer/visitor.phtml',
        	'width'		=> 200
        ));
        // add a column for the referer data
        $this->addColumn('referer', array(
            'header'    => Mage::helper('piwik')->__('Referer'),
            'sortable'  => false,
            'renderer'  => 'piwik/adminhtml_report_grid_column_renderer_generic',
            'tmpl'  	=> 'piwik/report/grid/column/renderer/referer.phtml'
        ));
        // add a column for the pages visited
        $this->addColumn('pages', array(
            'header'    => Mage::helper('piwik')->__('Pages'),
            'sortable'  => false,
            'renderer'  => 'piwik/adminhtml_report_grid_column_renderer_generic',
            'tmpl'  	=> 'piwik/report/grid/column/renderer/pages.phtml'
        ));
        // turn off filter and pager
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
		// call the method of the parent class
        return parent::_prepareColumns();
    }
}