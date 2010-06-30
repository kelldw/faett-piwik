<?php
/**
 * Faett_Piwik_Block_Adminhtml_Dashboard_Grids
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
 * Adminhtml dashboard bottom tabs
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Block_Adminhtml_Dashboard_Grids 
	extends Mage_Adminhtml_Block_Dashboard_Grids {
    
    /**
     * Adds the live user tab to the 
     * grid.
     * 
     * @return Faett_Piwik_Block_Adminhtml_Dashboard_Grids The instance
     */
    protected function _prepareLayout()
    {
		// call the parent method
        parent::_prepareLayout();
		// add the tab
        $this->addTab('live_user', array(
            'label'     => $this->__('Live User'),
            'url'       => $this->getUrl('*/*/liveUser', array('_current' => true)),
            'class'     => 'ajax'
        ));
        // return the instance
        return $this;
    }
}
