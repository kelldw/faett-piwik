<?php

/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_Tab
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
class Faett_Piwik_Block_Adminhtml_Dashboard_Tab 
	extends Mage_Adminhtml_Block_Template {
    
    /**
     * Returns TRUE if the PIWIK integration is activated, else 
     * FALSE.
     * 
     * Depending on this flag the tab for the tracking data
     * is rendered or not.
     * 
     * @return boolean 
     * 		TRUE if the PIWIK integration is activated, else FALSE
     */
    public function isActive() 
    {
    	// if PIWIK tracking is activated return TRUE
    	if (Mage::getStoreConfig('piwik/piwik/active')) {
    		return true;
    	}
    	// return FALSE otherwise
    	return false;
    }
}