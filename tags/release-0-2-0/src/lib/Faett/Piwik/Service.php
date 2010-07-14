<?php

/**
 * Faett_Piwik_Chart
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
 * Service class for the Piwik API.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Service
{
	
	/**
	 * Available methods with decoder mapping.
	 * @var array
	 */
	protected $_methods = array(
		'Live' => array(
			'getLastVisits'
		),
		'VisitsSummary' => array(
			'get'
		)
	);
	
	/**
	 * The data format to returen, can be one of JSON, PHP, XML or CSV.
	 * @var unknown_type
	 */
	protected $_format = 'Json';

	/**
	 * The Piwik API class to use.
	 * @var string
	 */
	protected $_class = '';
	
	/**
	 * The Piwik API method to invoke. 
	 * @var string
	 */
	protected $_method = '';
	
	/**
	 * Initializes the instance.
	 * 
	 * @return void
	 */
	protected function __construct()
	{
		/* Prevents class from direct instanciation */	
	}
	
	/**
	 * Factory method to create a new instance
	 * of the service.
	 * 
	 * @return Faett_Piwik_Service The instance
	 */
	public static function create()
	{
		return new Faett_Piwik_Service();
	}
    
    /**
     * Returns the Piwik site ID from the
     * configuration.
     * 
     * @return string The site ID
     */
    protected function _getSiteId()
    {
    	return Mage::getStoreConfig('piwik/piwik/account');
    }
    
    /**
     * Returns the Piwik Token necessary for
     * authentication purposes.
     * 
     * @return string The Piwik Token
     */
    protected function _getTokenAuth()
    {
    	return Mage::getStoreConfig('piwik/piwik_chart/token');
    }
    
    /**
     * Returns the Piwik URL specified in the
     * configuration.
     * 
     * @return string The Piwik URL
     */
    protected function _getPiwikUrl() 
    {
    	return Mage::getStoreConfig('piwik/piwik/url');
    }
    
    /**
     * Sets the Piwik API class to use
     * 
     * @param string $class The Piwik API class to use
     * @return void
     * @link http://dev.piwik.org/trac/wiki/API
     */
    protected function _setClass($class) 
    {
    	$this->_class = $class;	
    }
    
    /**
     * Returns the Piwik API class
     * to use.
     * 
     * @return string The Piwik API class to use
     * @link http://dev.piwik.org/trac/wiki/API
     */
    protected function _getClass()
    {
    	return $this->_class;
    }
    
    /**
	 * Returns the method to call to the PIWIK API, has
	 * to be one of the available PIWIK API methods.
	 * 
	 * @return string The method to call
     * @link http://dev.piwik.org/trac/wiki/API
     */
    protected function _getMethod()
    {
    	return $this->_method;
    }
    
    /**
	 * Sets the method to call to the Piwik API, has
	 * to be one of the available Piwik API methods.
	 * 
     * @param string $method The method to call
     * @return void
     * @link http://dev.piwik.org/trac/wiki/API
     */
    protected function _setMethod($methodName)
    {
    	$this->_method = $methodName;
    }
    
    /**
     * The format to return the data in, defaults
     * to 'JSON' but can be 'XML' or 'PHP' also.
     * 
     * @return string Data format for returning the values
     * @link http://dev.piwik.org/trac/wiki/API
     */
    protected function _getFormat()
    {
    	return $this->_format;
    }
    
    /**
     * Returns the URL for gathering the data from
     * Piwik.
     * 
     * @param array $params Additional params to add
     * @return string The piwik URL
     */
    public function build(array $params = array())
    {
    	// initialize the query string for additional parameters
    	$queryString =  
    		'http://'. $this->_getPiwikUrl() . 
    		'index.php' . 
    		'?module=API' . 
    		'&method=' . $this->_getClass() . '.' . $this->_getMethod() . 
    		'&idSite=' . $this->_getSiteId() . 
    		'&token_auth=' . $this->_getTokenAuth() .
    		'&format=' . $this->_getFormat();    	
    	// build the query string if parameters are available
    	foreach ($params as $key => $value) {
    		$queryString .= '&' . $key . '=' . urlencode($value);
    	}
    	// return the Piwik URL
    	return $queryString;
    }
    
    /**
     * This method calls the Piwik API with the passed
     * method and additional params.
     * 
     * @param string $methodName The method name to call
     * @param array $params Additional params for the Piwik API 
     */
    public function call($requestedMethod, array $params = array()) {
    	// extract the class and the method name
    	list($class, $method) = explode('.', $requestedMethod);
    	// check if the class is implemented
    	if (!array_key_exists($class, $this->_methods)) {
    		throw new Exception('Class ' . $class . ' not implemented');
    	}
    	// set the class
    	$this->_setClass($class);
    	// load the implemented methods of the class
    	$methods = $this->_methods[$class];
    	// check if the requested method is implemented
    	if (!in_array($method, $methods)) {
    		throw new Exception('Method ' . $method . ' not implemented');
    	}
    	// set the method
    	$this->_setMethod($method);
    	// initialize the loader class
    	$decoderClass = 'Faett_Piwik_Decoder_' . 
    		$this->_getFormat() . '_' .
    		$this->_getClass();
    	// instanciate the loader class
    	$reflectionClass = new ReflectionClass($decoderClass);
    	// initialize the decoder to load and assemble the data
    	$instance = $reflectionClass->newInstanceArgs(array($this));
    	$reflectionMethod = $reflectionClass->getMethod(
 			$this->_getMethod()
 		);
    	// load, decode and return the requested data
 		return $reflectionMethod->invokeArgs($instance, array($params));    	
    }
}