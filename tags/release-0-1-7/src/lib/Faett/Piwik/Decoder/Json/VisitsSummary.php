<?php

/**
 * Faett_Piwik_Decoder_Json_VisitsSummary
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
 * Decoder for loading and decoding the live user
 * data from Piwik in Json format and assebling a
 * collection.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Decoder_Json_VisitsSummary
	extends Faett_Piwik_Decoder_Json_Abstract
	implements Faett_Piwik_Decoder_Interfaces_Decoder {
	
	/**
	 * Loads the data from the Piwik instance and
	 * ass assembles a collection with the summary
	 * of the visits for a given time.
	 * 
	 * @param array $params 
	 * 		Array with additional params for requesting the Piwik API
	 * @return Varien_Data_Collection The collection with the live users
	 */
	public function get(array $params)
	{
		// load the data and decode it
		$phpNative = $this->decode($params);
		// initialize the collection
    	foreach ($phpNative as $date => $values) {
    		// pass the data
    		if (is_array($values)) {
    			// check if a value for the unique visitors exists
    			if (array_key_exists('nb_uniq_visitors', $values)) {
		    		// if yes, initialize an empty object
		    		$obj = new Varien_Object();
    				$obj->addData($values);
    				$obj->setUsers($values['nb_uniq_visitors']);
    				$obj->setRange($date);
			    	// add the object to the collection
		    		$this->_collection->addItem($obj);
    			}	
    		}
    	}
    	// return the collection
    	return $this->_collection;
	}
}