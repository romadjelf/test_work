<?php

class Zhupanyn_Youtube_Block_Youtube extends Mage_Core_Block_Template
{
    /**
     * Ид блока в который будем выводить видео с ютуба
     */
    const PLAYER_ID = 'zhupanyn_youtube_youtube_player';

    protected $_product = null;

    /**
     * Получение текущего товара
     *
     * @return Mage_Catalog_Model_Product|null
     */
    public function getProduct()
    {
        if (!$this->_product) {
            $this->_product = Mage::registry('product');
        }
        return $this->_product;
    }

    /**
     * Получение массива общей информации о видео для данного товара
     *
     * @return array|bool
     * @throws Varien_Exception
     */
    public function getYoutubeData()
    {
        $id_product = $this->getProduct()->getId();
        /* @var $youtubeModel Zhupanyn_Youtube_Model_Youtube*/
        $youtubeModel = Mage::getModel('zhupanyn_youtube/youtube');

        $youtubeData = false;
        if ($youtubeModel->isLoadYoutubeData($id_product)) {
            $youtubeData = [
                'id_youtube' => $youtubeModel->getIdYoutube(),
                'url_thumbnail' => $youtubeModel->getUrlThumbnail(),
                'title' => $youtubeModel->getTitle(),
                'published_at' => $youtubeModel->getPublishedAt(),
                'description' => nl2br($youtubeModel->getDescription()),
            ];
        }

        return $youtubeData;
    }
}