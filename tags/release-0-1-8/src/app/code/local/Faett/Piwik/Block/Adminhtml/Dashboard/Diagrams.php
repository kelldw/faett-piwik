<?php

/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_Diagrams
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
 * Adminhtml dashboard diagram tabs.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */

class Faett_Piwik_Block_Adminhtml_Dashboard_Diagrams 
	extends Mage_Adminhtml_Block_Dashboard_Diagrams {

	/**
	 * Adds the tab for the tracking chart
	 * to the tab bar.
	 * 
	 * @return Faett_Piwik_Block_Adminhtml_Dashboard_Diagrams The instance
	 */
    protected function _prepareLayout()
    {
        // initialize the parent tabs
        parent::_prepareLayout();
    	// check if tracking is enabled
        if (!Mage::getStoreConfigFlag('piwik/piwik/active')) {
            return;
        }
		// add the tab for the tracking chart
    	$this->addTab('tracking', array(
            'label'     => $this->__('Tracking'),
            'content'   => $this->getLayout()->createBlock('piwik/adminhtml_dashboard_tab_tracking')->toHtml(),
        ));
        // return the instance itself
        return $this;
    }
}