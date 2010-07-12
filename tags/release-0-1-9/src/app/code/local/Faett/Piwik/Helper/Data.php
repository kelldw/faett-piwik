<?php

/**
 * Faett_Piwik_Helper_Data
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
 * Piwik helper.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Helper_Data extends Mage_Adminhtml_Helper_Dashboard_Abstract
{
	
    /**
     * Prepare array with periods for dashboard graphs
     *
     * @return array
     */
    public function getDatePeriods()
    {
        return array(
            '7d'   => $this->__('Last 7 days'),
            '1m'   => $this->__('Current Month'),
            '1y'   => $this->__('YTD'),
            '2y'   => $this->__('2YTD')
        );
    }

    /**
     * Initializes the collection with the data  
     * necessary for rendering the chart.
     * 
     * @return void
     */
    protected function _initCollection()
    {	
    	// initialize the array with the params
    	$params = array(
    		'period' => 'day',
    		'date' 	 => 'last30'
    		
    	);
		// check the selected period
        switch ($period = $this->getParam('period'))
        {
            case '7d':
		    	break;
            case '1m':
		    	$params['period'] = 'day';
		    	$params['date'] = 'last30';
               	break;
            case '1y':
            	$params['period'] = 'month';
            	$params['date'] = 'last12';
		    	break;
            case '2y':
            	$params['period'] = 'month';
            	$params['date'] = 'last24';
		    	break;
            default:
            	throw new Exception(
            		'Invalid period ' . $period
            	);
        }
    	// initialize the Piwik service
    	$service = Faett_Piwik_Service::create();
    	// load the live user data
		$this->_collection = $service->call('VisitsSummary.get', $params);
    }

    /**
     * Create data hash to ensure that we got valid
     * data and it is not changed by some one else.
     *
     * @param string $data The data to generate the hash for
     * @return string The md5 hash of the passed data
     * @deprecated Because of 1.3.2.x backwards compatibility
     */
    public function getChartDataHash($data)
    {
        $secret = (string)Mage::getConfig()->getNode(Mage_Core_Model_App::XML_PATH_INSTALL_DATE);
        return md5($data . $secret);
    }
}