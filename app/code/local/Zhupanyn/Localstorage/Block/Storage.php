<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 09.08.18
 * Time: 16:58
 */
class Zhupanyn_Localstorage_Block_Storage extends Mage_Core_Block_Template
{

    public function getProductRegistry()
    {
        if ( Mage::registry('product') ) {

            $product = Mage::registry('product');
            /* @var $reports Mage_Reports_Block_Product_Viewed */
            $reports = Mage::getBlockSingleton('reports/product_viewed');
            $product_array = array(
                'product_id'            => $product->getId(),
                'product_url'   => $reports->getProductUrl($product),
                'img_src'       => $reports->helper('catalog/image')->init($product, 'thumbnail')->resize(50, 50)->setWatermarkSize('30x10')->__toString(),
                'img_alt'       => $reports->escapeHtml($reports->getProductName()),
                'product_name'  => $reports->helper('catalog/output')->productAttribute($product, $product->getName(), 'name')
            );
        } else {
            $product_array = array(
                'product_id' => null
            );
        }

        return $product_array;
    }

    public function getPageSize()
    {
        $reports = Mage::getBlockSingleton('reports/product_viewed');
        return $reports->getPageSize();
        //1111 2222
    }

}
?>