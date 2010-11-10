<?php

/**
 * Faett_Piwik_Block_Tag
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
 * Piwik Block page.
 *
 * @category   	Faett
 * @package    	Faett_Piwik
 * @copyright  	Copyright (c) 2009 <tw@faett.net> Tim Wagner
 * @license    	<http://www.gnu.org/licenses/> 
 * 				GNU General Public License (GPL 3)
 * @author      Tim Wagner <tw@faett.net>
 */
class Faett_Piwik_Block_Tag extends Mage_Core_Block_Text
{

    /**
     * Retrieve Piwik Account Identifier
     *
     * @return string
     */
    public function getAccount()
    {
        if (!$this->hasData('account')) {
            $this->setAccount(Mage::getStoreConfig('piwik/piwik/account'));
        }
        return $this->getData('account');
    }

    /**
     * Retrieve Piwik URL
     *
     * @return string
     */
    public function getPiwikUrl()
    {
        if (!$this->hasData('piwik_url')) {
            $this->setPiwikUrl(Mage::getStoreConfig('piwik/piwik/url'));
        }
        return $this->getData('piwik_url');
    }

    /**
     * Retrieve current page URL
     *
     * @return string
     */
    public function getPageName()
    {
        if (!$this->hasData('page_name')) {
            $this->setPageName(Mage::getSingleton('core/url')->escape($_SERVER['REQUEST_URI']));
        }
        return $this->getData('page_name');
    }

    /**
     * Prepare and return block's html output
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!Mage::getStoreConfigFlag('piwik/piwik/active')) {
            return '';
        }

        $html = '<!-- Piwik -->';
        $html .= '<script type="text/javascript">';
        $html .= '    var pkBaseURL = (("https:" == document.location.protocol) ? "https://' . $this->getPiwikUrl() . '" : "http://' . $this->getPiwikUrl() . '");';
        $html .= '    document.write(unescape("%3Cscript src=\'" + pkBaseURL + "piwik.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
        $html .= '</script>';
        $html .= '<script type="text/javascript">';
        $html .= '    try {';
        $html .= '        var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", ' . $this->getAccount() . ');';
        $html .= '        piwikTracker.trackPageView();';
        $html .= '        piwikTracker.enableLinkTracking();';
        $html .= '    } catch( err ) {}';
        $html .= '</script>';
        $html .= '<noscript><p><img src="http://' . $this->getPiwikUrl() . 'piwik.php?idsite=' . $this->getAccount() . '" style="border:0" alt=""/></p></noscript>';
        $html .= '<!-- End Piwik Tag -->';

        $this->addText($html);

        return parent::_toHtml();
    }
}