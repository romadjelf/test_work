<?php

class Zhupanyn_Youtube_Model_Youtube extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('zhupanyn_youtube/youtube');
    }

    /**
     * Сохранение данных о ютуб видео втаблице zhupanyn_youtube
     * по ссылке на видео с товара
     *
     * @param string $url
     * @param integer $id_product
     * @return $this Zhupanyn_Youtube_Model_Youtube
     * @throws Varien_Exception
     */
    public function saveYoutubeData($url, $id_product)
    {
        if (empty($url)) {
            $this->deleteYoutubeData($id_product);
        } else {
            $youtubeDataByUrl = $this->getYoutubeDataByUrl($url);
            $id_youtube = key($youtubeDataByUrl);

            if (empty($id_youtube)) {
                $this->deleteYoutubeData($id_product);
            } else {
                if (!$this->isLoadYoutubeData($id_product)) {
                    $this->setIdProduct($id_product);
                }
                $this->setIdYoutube($id_youtube);
                $this->addData($this->getSnippetData($youtubeDataByUrl[$id_youtube]));
                $this->save();
            }
        }
        return $this;
    }

    /**
     * Удаление данных о видео
     *
     * @param integer $id_product
     * @return $this Zhupanyn_Youtube_Model_Youtube
     * @throws Exception
     */
    public function deleteYoutubeData($id_product)
    {
        if ($this->isLoadYoutubeData($id_product)) {
            $this->delete();
        }
        return $this;
    }

    /**
     * Получение данных с ютуба по ссылке на видео
     *
     * @param string $url
     * @return array
     */
    protected function getYoutubeDataByUrl($url)
    {
        /* @var $youtube Zhupanyn_Youtube_Helper_YouTube_Videos*/
        $youtube = Mage::helper('zhupanyn_youtube/youtube_videos');

        $videoDataByUrl = $youtube->getVideoDataByUrl($url, [Zhupanyn_Youtube_Helper_YouTube_Videos::PART_STATISTICS]);

        return $videoDataByUrl;
    }

    /**
     * С данных полученых с ютуба выбрать только общую информацию
     * для отдельного видео
     *
     * @param $youtubeData
     * @return mixed
     */
    protected function getSnippetData($youtubeData)
    {
        return $youtubeData[Zhupanyn_Youtube_Helper_YouTube_Videos::PART_SNIPPET];
    }

    /**
     * Проверка есть ли данные о ютуб видео для конкретного товара
     * в таблице zhupanyn_youtube
     *
     * @param integer $id_product
     * @return bool
     */
    public function isLoadYoutubeData($id_product)
    {
        $this->load($id_product,'id_product');
        if (empty($this->getOrigData())) {
            $isLoad = false;
        } else {
            $isLoad = true;
        }
        return $isLoad;
    }
}