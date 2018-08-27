<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 13.08.18
 * Time: 11:23
 */
class Zhupanyn_Localstorage_AjaxController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function productsAction()
    {
        $productArray = array();
        /* @var $reports Mage_Reports_Block_Product_Viewed */
        $reports = Mage::getBlockSingleton('reports/product_viewed');
        $collection = $reports->getItemsCollection();
        foreach ($collection as $product){
            $productArray[] = array(
                'product_id'    => $product->getId(),
                'product_url'   => $reports->getProductUrl($product),
                'img_src'       => $reports->helper('catalog/image')->init($product, 'thumbnail')->resize(50, 50)->setWatermarkSize('30x10')->__toString(),
                'img_alt'       => $reports->escapeHtml($reports->getProductName()),
                'product_name'  => $reports->helper('catalog/output')->productAttribute($product, $product->getName(), 'name')
            );
        }
        $jsonStr = Mage::helper('core')->jsonEncode($productArray);

        $this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        $this->getResponse()->setBody($jsonStr);
    }

}