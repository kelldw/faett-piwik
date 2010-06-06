<?php

/**
 * Faett_Piwik_Block_Adminhtml_Report_Grid_Column_Renderer_Generic
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
 * Adminhtml grid item generic renderer.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Block_Adminhtml_Report_Grid_Column_Renderer_Generic 
	extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {
	
	/**
	 * The row with the data to render.
	 * @var Varien_Object
	 */
	private $_row = null;
	
	/**
	 * Returns the value for the passed attribute.
	 * 
	 * @param string $attributeName The attribute name to return the value for
	 * @return mixed The requested value
	 */
	public function getAttributeValue($attributeName)
	{
		return $this->_row->getData($attributeName);
	}
	
	/**
	 * Returns the requested values as string, concatenated 
	 * with the glue passed as parameter.
	 * 
	 * @param array $attributeNames The requested attribute names
	 * @param string $glue The glue to concatenate the values with
	 */
	public function getAttributeValues(array $attributeNames, $glue = ', ')
	{
		// initialize an array for the values
		$values = array();
		// load the values and add them to the array
		for($i = 0; $i < sizeof($attributeNames); $i++) {
			$values[] = $this->_row->getData($attributeNames[$i]);
		}
		// concatenate the values by using the glue
		return implode($glue, $values);
	}
	
	/**
	 * Returns the Piwik URL, necessary for rendering the 
	 * image path.
	 * 
	 * @return string The Piwik URL
	 */
	public function getPiwikUrl()
	{
		return 'http://' . Mage::getStoreConfig('piwik/piwik/url');
	}
	
	/**
	 * Returns the complete path to the Piwik image 
	 * for the attribute with the passed name.
	 * 
	 * @param string $attributeName The attribute with the image
	 */
	public function getImageSrc($attributeName)
	{
		return $this->getPiwikUrl() . $this->getAttributeValue($attributeName);
	}
	
    /**
     * Renders grid column.
     *
     * @param Varien_Object $row The row data
     * @return string The HTML source
     */
	public function render(Varien_Object $row)
	{
        // set the row
		$this->_row = $row;
		// set the specified template
        $this->setTemplate($this->getColumn()->getTmpl());
		// render the column
		return $this->renderView();
	}
}