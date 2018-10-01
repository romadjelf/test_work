<?php
class Zhupanyn_Youtube_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @throws Mage_Core_Exception
     */
    public function validateYoutubeAttribute(Varien_Event_Observer $observer)
    {
        $product = $observer->getProduct();
        $url = $product->getYoutubeVideo();

        /* @var $youtube Zhupanyn_Youtube_Helper_YouTube_Videos*/
        $youtube = Mage::helper('zhupanyn_youtube/youtube_videos');

        /*//по url
        $url = 'https://www.youtube.com/watch?v=efRm7fW9hL4&start_radio=1&list=RDefRm7fW9hL4';
        $videoDataFromUrl = $youtube->getVideoDataByUrl($url, [Zhupanyn_Youtube_Helper_YouTube_Videos::PART_STATISTICS]);

        //по ids
        $videoDataFromIds = $youtube->getVideoDataById('efRm7fW9hL4,ojJJaB-SqEs', [Zhupanyn_Youtube_Helper_YouTube_Videos::PART_STATISTICS] );*/

        $arrayProd = [
            ['id_product'=>5, 'youtube_link'=>'https://www.youtube.com/watch?v=efRm7fW9hL4&start_radio=1&list=RDefRm7fW9hL4'],
            ['id_product'=>10, 'youtube_link'=>'https://www.youtube.com/watch?v=ojJJaB-SqEs'],
        ];
        $videoDataFromProdArray = $youtube->getVideoDataByArray($arrayProd, [Zhupanyn_Youtube_Helper_YouTube_Videos::PART_STATISTICS]);

        $x = 1;

        Mage::throwException('Error youtube: '.$url);
    }
}