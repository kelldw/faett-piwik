<?php

/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_Graph
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
 * Adminhtml for the dashboard with the google chart's.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Block_Adminhtml_Dashboard_Graph 
	extends Mage_Adminhtml_Block_Dashboard_Graph {

	/**
	 * Initialize the Block with the path to the
	 * template to use.
	 * 
	 *  @return void
	 */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('piwik/dashboard/graph.phtml');
    }

    /**
     * Returns the path to the template to use.
     * 
     * @return string The path to the template
     */
    protected function _getTabTemplate()
    {
        return 'piwik/dashboard/graph.phtml';
    }

    /**
     * (non-PHPdoc)
     * @see app/code/core/Mage/Adminhtml/Block/Dashboard/Mage_Adminhtml_Block_Dashboard_Graph#getChartUrl($directUrl = true)
     */
    public function getChartUrl($directUrl = true)
    {	
    	// check if google chart API should be used or internal chart library
    	if (Mage::getStoreConfig('piwik/piwik_chart/google')) {
    		return parent::getChartUrl($directUrl);
    	}
		// load the data for the series
        $this->_allSeries = $this->getRowsData($this->_dataRows);
		// initialize the axis labels
        foreach ($this->_axisMaps as $axis => $attr) {
            $this->setAxisLabels($axis, $this->getRowsData($attr, true));
        }
		// load the start and the end date
        list ($dateStart, $dateEnd) = Mage::getResourceModel('reports/order_collection')
            ->getDateRange($this->getDataHelper()->getParam('period'), '', '', true);
		// initialize the arrays for the data
        $dates = array();
        $datas = array();
		// assemble the arrays with the data and the dates
        while($dateStart->compare($dateEnd) < 0){
            switch ($this->getDataHelper()->getParam('period')) {
                case '24h':
                    $d = $dateStart->toString('yyyy-MM-dd HH:00');
                    $dateStart->addHour(1);
                    break;
                case '7d':
                case '1m':
                    $d = $dateStart->toString('yyyy-MM-dd');
                    $dateStart->addDay(1);
                    break;
                case '1y':
                case '2y':
                    $d = $dateStart->toString('yyyy-MM');
                    $dateStart->addMonth(1);
                    break;
            }
            foreach ($this->getAllSeries() as $index => $serie) {
                if (in_array($d, $this->_axisLabels['x'])) {
                    $datas[$index][] = (float)array_shift($this->_allSeries[$index]);
                } else {
                    $datas[$index][] = 0;
                }
            }
            $dates[] = $d;
        }
        // initialize the array for the assembled data
		$params = array();
		// iterate over the dates
		foreach ($dates as $key => $date) {
        	// format the date
			switch ($this->getDataHelper()->getParam('period')) {
            	case '24h':
                	$date = $this->formatTime($date, 'short', false);
                	break;
            	case '7d':
           		case '1m':
                	$date = $this->formatDate($date);
                	break;
            	case '1y':
            	case '2y':
                	$formats = Mage::app()->getLocale()->getTranslationList('datetime');
                	$format = isset($formats['yyMM']) ? $formats['yyMM'] : 'MM/yyyy';
                	$format = str_replace(array("yyyy", "yy", "MM"), array("Y", "y", "m"), $format);
        			$date = date($format, strtotime($date));
                	break;
			}
			// assemble the date and the apropriate data
			foreach ($datas as $name => $values) {
				$params[] = array($date, $key, $datas[$name][$key]);
			}
		}        
        // return the encoded data
        if ($directUrl) {
            $p = array();
            foreach ($params as $name => $value) {
                $p[] = $name . '=' .urlencode($value);
            }
            return self::API_URL . '?' . implode('&', $p);
        } else {
            $gaData = urlencode(base64_encode(serialize($params)));
            $gaHash = Mage::helper('piwik')->getChartDataHash($gaData);
            $params = array('ga' => $gaData, 'h' => $gaHash);
            return $this->getUrl('*/*/tunnel', array('_query' => $params));
        }
    }

    /**
     * (non-PHPdoc)
     * @see app/code/core/Mage/Adminhtml/Block/Dashboard/Mage_Adminhtml_Block_Dashboard_Graph#getRowsData($attributes, $single)
     */
    protected function getRowsData($attributes, $single = false)
    {
    	// load the collection with the raw data        
        if (is_array($col = $this->getCollection())) {
        	$items = $col;
        } else {
        	$items = $col->getItems();
        }
        // initialize the array for the data
        $options = array();
        // iterate over the data and initialize the array
        foreach ($items as $item){
            if ($single) {
                $options[] = $item[$attributes];
            } else {
                foreach ((array)$attributes as $attr){
                    $options[$attr][] = $item[$attr];
                }
            }
        }
        // return the initialized data
        return $options;
    }
}