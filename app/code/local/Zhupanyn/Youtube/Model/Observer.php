<?php

class Zhupanyn_Youtube_Model_Observer
{
    /**
     * Сохранение данных о ютуб видео втаблице zhupanyn_youtube
     * по ссылке на видео с товара
     *
     * @param Varien_Event_Observer $observer
     * @throws Varien_Exception
     */
    public function saveYoutubeData(Varien_Event_Observer $observer)
    {
        $id_product = $observer->getProduct()->getId();
        $url        = $observer->getProduct()->getYoutubeVideo();

        /* @var $model Zhupanyn_Youtube_Model_Youtube*/
        $model = Mage::getModel('zhupanyn_youtube/youtube');
        $model->saveYoutubeData($url, $id_product);
    }

    /**
     * Добавление блока ютуб и скрипта для загрузки видео в карточку товара
     * если есть запись в таблице zhupanyn_youtube
     *
     * @param Varien_Event_Observer $observer
     * @throws Varien_Exception
     */
    public function addBlockYoutubeVideo(Varien_Event_Observer $observer)
    {
        $layout = $observer->getLayout();
        $catalog_prod_view = $layout->getBlock('product.info');

        if ($catalog_prod_view){
            /* @var $newBlock Zhupanyn_Youtube_Block_Youtube */
            $newBlock = $layout->createBlock('zhupanyn_youtube/youtube', 'zhupanyn.youtube.youtube', ['template'=>'zhupanyn/youtube/view.phtml']);
            $id_product = $newBlock->getProduct()->getId();

            /* @var $youtubeModel Zhupanyn_Youtube_Model_Youtube */
            $youtubeModel = Mage::getModel('zhupanyn_youtube/youtube');

            if ($youtubeModel->isLoadYoutubeData($id_product)) {

                $catalog_prod_view->append($newBlock);
                $newBlock->addToParentGroup('detailed_info');
                $newBlock->setTitle('Youtube');

                /* @var $head Mage_Page_Block_Html_Head */
                $head = $layout->getBlock('head');
                $head->addJs('zhupanyn/youtube/main.js');
            }
        }
    }
}