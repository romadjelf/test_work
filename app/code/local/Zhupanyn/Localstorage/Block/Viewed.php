<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 13.08.18
 * Time: 9:10
 */
class Zhupanyn_Localstorage_Block_Viewed extends Mage_Reports_Block_Product_Viewed
{
    protected function _toHtml()
    {
        if (!$this->getCount()) {
            return '';
        }
        $this->setRecentlyViewedProducts('Hello!!');
        return "Продуктів {$this->getCount()} <br /> Привіт: {$this->getRecentlyViewedProducts()}";

        /*if (!$this->getCount()) {
            return '';
        }
        $this->setRecentlyViewedProducts(array());
        return parent::_toHtml();*/
        //Mage_Catalog_Block_Product_View

    }

}