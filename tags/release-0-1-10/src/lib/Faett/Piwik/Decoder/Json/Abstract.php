<?php

/**
 * Faett_Piwik_Decoder_Json_Abstract
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
 * Abstract decoder implementation for loading and decoding 
 * the data from Piwik in JSON format.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Decoder_Json_Abstract
	implements Faett_Piwik_Decoder_Interfaces_Decoder {
		
	/**
	 * The collection with the data gathered from the Piwik instance
	 * @var Varien_Data_Collection
	 */
	protected $_collection = null;

	/**
	 * The service instance that invoked the request.
	 * @var Faett_Piwik_Service
	 */
	protected $_service = null;
	
	/**
	 * Initializes the decoder with the
	 * passed params.
	 * 
	 * @param Faett_Piwik_Service $service
	 * @param array $params
	 */
	public function __construct(
		Faett_Piwik_Service $service) {
		// set the service and the params to use
		$this->_service = $service;
		// initialize the collection
		$this->_collection = new Varien_Data_Collection();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see src/lib/Faett/Piwik/Decoder/Interfaces/Faett_Piwik_Decoder_Interfaces_Decoder#decode($params)
	 */
	public function decode(array $params)
	{	    		
    	// check of the piwik API can be invoked and read the data
    	$toDecode = @file_get_contents(
    		$piwikUrl = $this->_service->build($params)
    	);
    	// check if the data can be loaded successfully
    	if ($toDecode === false) {
    		throw new Exception(
    			'Invalid piwik URL ' . $piwikUrl . ' specified'
    		);
    	}    	
    	// load, decode and return the data
    	return Zend_Json::decode(
    		$toDecode, 
    		Zend_Json::TYPE_ARRAY
    	);
	}
}