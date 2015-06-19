<?php class TheExtensionLab_MegaMenuCatalinSeo_Model_Observer
{
    public function megamenuGetfilterurlAfter(Varien_Event_Observer $observer)
    {
        $urlData = $observer->getUrlData();
        $query = $urlData->getQuery();
        $url = $urlData->getUrl();

        $urlData->setUrl($this->getSeoUrl($query,$url));

        return $this;
    }

    private function getSeoUrl($q,$url)
    {
        $url = strtok($url, '?');
        $urlPath = '';

        unset($q['p']);
        // Add filters
        $layerParams = $q;
        foreach ($layerParams as $key => $value) {
            // Encode and replace escaped delimiter with the delimiter itself
            $value = str_replace(urlencode(Catalin_SEO_Helper_Data::MULTIPLE_FILTERS_DELIMITER), Catalin_SEO_Helper_Data::MULTIPLE_FILTERS_DELIMITER, urlencode($value));
            $urlPath .= "/{$key}/{$value}";
        }

        $suffix = Mage::getStoreConfig('catalog/seo/category_url_suffix');

        $urlParts = explode('?', $url);

        $urlParts[0] = substr($urlParts[0], 0, strlen($urlParts[0]) - strlen($suffix));
        // Add the suffix to the url - fixes when coming from non suffixed pages
        // It should always be the last bits in the URL
        $urlParts[0] .= Mage::helper('catalin_seo')->getRoutingSuffix();

        $url = $urlParts[0] . $urlPath . $suffix;
        if (!empty($urlParts[1])) {
            $url .= '?' . $urlParts[1];
        }

        return $url;
    }
}