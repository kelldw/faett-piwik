<?php

/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_Tab_Tracking
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
 * Adminhtml dashboard order tracking diagram.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */

class Faett_Piwik_Block_Adminhtml_Dashboard_Tab_Amounts 
	extends Faett_Piwik_Block_Adminhtml_Dashboard_Graph {
	
	/**
	 * Constructor to initialize the amounts tab.
	 * 
	 * @return void
	 */
    public function __construct()
    {
        $this->setHtmlId('amounts');
        parent::__construct();
    }
    
    /**
     * Returns the date periods for the select box.
     * 
     * @return array The available date periods
     */
    public function getDatePeriods()
    {
    	return $this->helper('adminhtml/dashboard_data')->getDatePeriods();
    }

    /**
     * Prepares the data for rendering the
     * amounts chart.
     * 
     * @return void
     */
    protected function _prepareData()
    {
    	// set the helper for loading the data
        $this->setDataHelperName('adminhtml/dashboard_order');
        // set the params
        $this->getDataHelper()->setParam('store', $this->getRequest()->getParam('store'));
        $this->getDataHelper()->setParam('website', $this->getRequest()->getParam('website'));
        $this->getDataHelper()->setParam('group', $this->getRequest()->getParam('group'));
        $this->getDataHelper()->setParam(
            'period',
            $this->getRequest()->getParam('period')?$this->getRequest()->getParam('period'):'24h'
        );
		// initialize the chart
        $this->setDataRows('revenue');
        $this->_axisMaps = array(
            'x' => 'range',
            'y' => 'revenue');
		// call the parent method
        parent::_prepareData();
    }
}