<?php

/**
 * Faett_Piwik_Adminhtml_DashboardController
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

require_once 'Mage/Adminhtml/controllers/DashboardController.php';

/**
 * Controller for the admin Dashboard.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Adminhtml_DashboardController 
	extends Mage_Adminhtml_DashboardController {

	/**
	 * The idenitifier for handling the serialz
	 * @var string
	 */
    protected $_identifier = 'faett/Faett_Piwik';

    /**
     * Method is called by the controller before the requested action is
     * invoked.
     *
     * This method checks if a valid licence for the package is available,
     * if not the user is redirected to the package detail page to enter
     * a valid serialz.
     *
     * @return Mage_Core_Controller_Front_Action The controller instance
     */
    public function preDispatch()
    {
        try {
            // invoke the parent's preDispatch method
            parent::preDispatch();
            // validate the package information
            Mage::getModel('manager/connector')->validate(
                $packageInformation = $this->_getPackageInformation()
            );
            // return the instance
            return $this;
        } catch(Faett_Manager_Exceptions_InvalidLicenceException $ile) {
            // log the exception
            Mage::logException($ile);
            // add an error to the session
        	Mage::getSingleton('adminhtml/session')->addError(
        	    Mage::helper('piwik')->__(
        	    	'100.error.invalid.serialz',
        	    	$packageInformation->getIdentifier()
        	    )
        	); 
        } catch(Faett_Manager_Exceptions_ChannelLoginException $cle) {
            // log the exception
            Mage::logException($cle);
            // add an error to the session
        	Mage::getSingleton('adminhtml/session')->addError(
        	    Mage::helper('piwik')->__(
        	    	'100.error.invalid.credentials',
        	    	$packageInformation
        	    	    ->getPackage()
        	    	    ->getChannel()
        	    	    ->getUrl()
        	    )
        	);
            // redirect and request user to enter a valid Serialz
            $this->_forward(
            	'index', 
            	'adminhtml_channel', 
            	'manager', 
            	array(
			    	'id' => $packageInformation->getPackage()->getId()
			    )
            );
        } catch(Faett_Manager_Exceptions_ChannelNotFoundException $cnfe) {
            // log the exception
            Mage::logException($cnfe);
            // add an error to the session
        	Mage::getSingleton('adminhtml/session')->addError(
        	    Mage::helper('piwik')->__(
        	        '100.error.invalid.channel',
        	    	$packageInformation
        	    	    ->getPackage()
        	    	    ->getChannel()
        	    	    ->getUrl()
        	    )
        	);
            // redirect and request user to register channel first
            $this->_forward(
            	'new', 
            	'adminhtml_channel', 
            	'manager'
            );
        }
    }

	/**
	 * Returns the unique package identifier containing
	 * the channels alias and the package name.
	 *
	 * @return string The unique Package identifier
	 */
	protected function _getIdentifier()
	{
	    return $this->_identifier;
	}

    /**
     * Details package information with Name, Channel and Serialz.
     *
     * @return Faett_Manager_Package_Interfaces_Information
     */
    protected function _getPackageInformation()
    {
        return Faett_Manager_Package_Information_Default::create()->init(
            $this->_getIdentifier()
        );
    }
    
    /**
     * (non-PHPdoc)
     * @see magento-src/app/code/core/Mage/Adminhtml/controllers/Mage_Adminhtml_DashboardController#tunnelAction()
     */
    public function tunnelAction()
    {
    	// check if google chart API should be used or internal chart library
    	if (Mage::getStoreConfig('piwik/piwik_chart/google')) {
    		parent::tunnelAction();
    	} else {
	        $gaData = $this->getRequest()->getParam('ga');
	        $gaHash = $this->getRequest()->getParam('h');
	        if ($gaData && $gaHash) {
	            $newHash = Mage::helper('piwik')->getChartDataHash($gaData);
	            if ($newHash == $gaHash) {
	                if ($params = unserialize(base64_decode(urldecode($gaData)))) {
						// render the chart and add the image to the response                	
	                    $this->getResponse()
	                        ->setHeader('Content-type', 'image/png')
	                        ->setBody($this->_render($params));
	                }
	            }
	        }
    	}
    }
    
    /**
	 * Renders the graph with PHPlot chart
	 * library.
	 * 
	 * @params array The array with the data to render the chart with
	 * @param resource The chart rendered as image
     */
    protected function _render($params) {
    	// initialize the chart class 
		$plot = new Faett_Piwik_Chart();
		// if TrueType fonts should be used set the magento default TTF
		if (Mage::getStoreConfig('piwik/piwik_chart/ttf')) {
			$plot->SetDefaultTTFont('lib/LinLibertineFont/LinLibertine_It-2.8.2.ttf');
		} else {
			$plot->SetFontGD('x_label', 2);
			$plot->SetFontGD('y_label', 2);
    	}
    	// set the data
		$plot->SetDataValues($params);
		// render and return the chart as image
		return $plot->DrawGraph();
    }
    
    /**
     * (non-PHPdoc)
     * @see magento-src/app/code/core/Mage/Adminhtml/controllers/Mage_Adminhtml_DashboardController#ajaxBlockAction()
     */
    public function ajaxBlockAction()
    {
        $output   = '';
        $blockTab = $this->getRequest()->getParam('block');
        if (in_array($blockTab, array('tab_tracking'))) {
            $output = $this->getLayout()->createBlock(
            	'piwik/adminhtml_dashboard_' . $blockTab
            )->toHtml();
        	$this->getResponse()->setBody($output);
        } else {
    		parent::ajaxBlockAction();
        }
        return;
    }
    
	/**
	 * Loads the data and reneders the grid with the
	 * Piwik live user data.
	 * 
	 *  @return void
	 */
    public function liveUserAction()
    {
    	// render the data and attach the grid to the response
        $this->getResponse()->setBody(
        	$this->getLayout()->createBlock(
        		'piwik/adminhtml_dashboard_tab_live_user'
        	)->toHtml()
        );
    }
}