<?php

/*
 * ConversionBug_Core
 *
 * @category     ConversionBug
 * @package      ConversionBug_Core
 * @copyright    Copyright (c) 2016 ConversionBug (http://www.conversionbug.com/)
 * @author       Ramesh Allamsetti
 * @email        ramesh.allamsetti@conversionbug.com
 * @version      Release: 1.2.0
 */

class ConversionBug_Core_Block_Info extends Mage_Adminhtml_Block_Template {

    /**
     * Print init JS script into body
     * @return string
     */
    protected function _toHtml() {
        $section = $this->getAction()->getRequest()->getParam('section', false);
        if ($section == 'cbstore') {
            //return parent::_toHtml();
        } else {
            return '';
        }
    }

    function urlGetContents() {
        $url = Mage::getUrl() . 'product.csv';
        $cUrl = curl_init();
        curl_setopt($cUrl, CURLOPT_URL, $url);
        curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cUrl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)");
        curl_setopt($cUrl, CURLOPT_TIMEOUT, 3);
        $content = curl_exec($cUrl);

        if (curl_getinfo($cUrl, CURLINFO_HTTP_CODE) != 200) {
            curl_close($cUrl);
            return false;
        } else {

            return $content;
        }
    }

}
