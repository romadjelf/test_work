<?php

require_once 'lib/googleapi/vendor/autoload.php';

class Zhupanyn_Youtube_Helper_YouTube_Videos extends Mage_Core_Helper_Abstract
{
    /**
     * Название разделов которые хотим получить из видео
     */
    const PART_SNIPPET = 'snippet';
    const PART_STATISTICS = 'statistics';

    /**
     * Ключи для получения доступа к включенным API
     * @var string
     */
    private $apiKey = 'AIzaSyAZMjY_O5nnfURo0f0sOrGpKJXykZHsHCU';

    /**
     * @var Google_Service_YouTube
     */
    private $youtube;

    /**
     * Данные ответа из запроса на получение информации об однои или несколько видео.
     * @var Google_Service_YouTube_VideoListResponse
     */
    private $videoResponse;

    /**
     * Данные обо всех видео с запроса в виде массива Google_Service_YouTube_Video
     * @var array|null
     */
    private $videos = null;

    /**
     * Ассоциативный массив где id_youtube ключ, а значение массив данных с видео.
     * @var array
     */
    private $assocYoutubeArray = [];

    /**
     * Массив всех названий разделов из видео.
     * Применяется для формирования результирующего списка разделов из входящего списка.
     * @var array
     */
    private $allPart = [
        self::PART_SNIPPET,
        self::PART_STATISTICS
    ];

    /**
     * Результирующий список разделов которые хотим получить из запроса.
     * @var array
     */
    private $finalPart;

    /**
     * Все id_youtube из входящего массива.
     *
     * @var string
     */
    private $idsYoutube;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setApplicationName('Zhupanyn_Youtube');
        $client->setDeveloperKey($this->apiKey);

        $this->youtube = new Google_Service_YouTube($client);
    }

    /**
     * Получение данных по одному или несколько видео в ассоциативном масиве
     * с id_youtube в виде ключа
     *
     * @param string $ids  один или несколько id_youtube через запятую
     * @param array $part  массив значений из констант PART_...
     * @return array
     */
    public function getVideoDataById($ids, $part = [])
    {
        $this->setFinalPart($part)
            ->setVideos($ids)
            ->fillAssocYoutubeArray();

        return $this->assocYoutubeArray;
    }

    /**
     * Получение данных по одному видео в ассоциативном масиве
     * с id_youtube в виде ключа
     *
     * @param string $url  ссылка видео с ютуба
     * @param array $part  массив значений из констант PART_...
     * @return array
     */
    public function getVideoDataByUrl($url, $part = [])
    {
        /* @var $regex Zhupanyn_Youtube_Helper_YouTube_Regex*/
        $regex = Mage::helper('zhupanyn_youtube/youtube_regex');
        $id = $regex->getIdYoutubeByLink($url);

        return $this->getVideoDataById($id, $part);
    }

    /**
     * Получение данных о видео в ассоциативном масиве с id_product и youtube_data
     * в виде ключей
     *
     * @param string $arrayProductLinks  массив ассоциативных массивов
     * с ключасми id_product и youtube_link
     * @param array $part  массив значений из констант PART_...
     * @return array
     */
    public function getVideoDataByArray($arrayProductLinks, $part = [])
    {
        $this->prepareProductData($arrayProductLinks)
            ->getVideoDataById($this->idsYoutube, $part);

        $assocProductArray = [];
        foreach ($this->assocYoutubeArray as $key=>$value) {
            $id_product = $value['id_product'];
            unset($value['id_product']);
            $value['id_youtube'] = $key;

            $assocProductArray[] = [
                'id_product'    => $id_product,
                'youtube_data'  => $value
            ];
        }
        return $assocProductArray;
    }

    /**
     * Получить данных запроса по одному или нескольких видео введя ключи через запятую.
     *
     * @param string $ids
     * @return Google_Service_YouTube_VideoListResponse
     */
    protected function getVideoResponseByIds($ids)
    {
        $part = $this->arrayToString($this->finalPart);
        $this->videoResponse = $this->youtube->videos->listVideos($part, [
            'id' => $ids,
            'maxResults' => 50
        ]);
        return $this->videoResponse;
    }

    /**
     * Получить данные о всех видео в массив $videos
     *
     * @param string $ids
     * @return Zhupanyn_Youtube_Helper_YouTube_Videos
     */
    protected function setVideos($ids)
    {
        $items = $this->getVideoResponseByIds($ids)->getItems();
        if (!empty($items)) {
            $this->videos = $items;
        }
        return $this;
    }

    /**
     * @param array $idArray
     * @return string
     */
    protected function arrayToString($idArray)
    {
        $stringIds = implode(',', $idArray);
        return $stringIds;
    }

    /**
     * Подготовить данные из входящего массива товаров
     *
     * @param $arrayProductLinks
     * @return Zhupanyn_Youtube_Helper_YouTube_Videos
     */
    protected function prepareProductData($arrayProductLinks)
    {
        /* @var $regex Zhupanyn_Youtube_Helper_YouTube_Regex*/
        $regex = Mage::helper('zhupanyn_youtube/youtube_regex');

        foreach ($arrayProductLinks as $item) {
            $id_youtube = $regex->getIdYoutubeByLink($item['youtube_link']);
            $this->assocYoutubeArray[$id_youtube] = [
                'id_product' => $item['id_product'],
                'id_youtube' => null
            ];
        }
        $idsYoutubeArray = array_keys($this->assocYoutubeArray);
        $this->idsYoutube = $this->arrayToString($idsYoutubeArray);

        return $this;
    }

    /**
     * Получение массива разделов которые надо получить по каждому видео
     *
     * @param array $part
     * @return Zhupanyn_Youtube_Helper_YouTube_Videos
     */
    protected function setFinalPart($part = [])
    {
        $mergeSnippetAndPart = array_merge($part, [self::PART_SNIPPET]);
        $this->finalPart = array_intersect($this->allPart, $mergeSnippetAndPart);

        return $this;
    }

    /**
     * Заполнение массива $assocYoutubeArray данными
     */
    protected function fillAssocYoutubeArray()
    {
        if (!empty($this->videos)) {
            foreach ($this->videos as $video) {
                $id_youtube = $video->getId();
                foreach ($this->finalPart as $part) {
                    $method = 'add'.ucfirst($part);
                    $this->assocYoutubeArray[$id_youtube][$part] = $this->{$method}($video);
                }
            }
        }
        return $this;
    }

    /**
     * Добавление базовой информации по видео
     *
     * @param Google_Service_YouTube_Video $video
     * @return array
     */
    protected function addSnippet($video)
    {
        $snippet = $video->getSnippet();

        $data = [
            'title' => $snippet->getTitle(),
            'description' => $snippet->getDescription(),
            'url_thumbnail' => $snippet->getThumbnails()->getDefault()->getUrl(),
            'publishedAt' => $snippet->getPublishedAt()
        ];
        return $data;
    }

    /**
     * Добавление статистики по видео
     *
     * @param Google_Service_YouTube_Video $video
     * @return array
     */
    protected function addStatistics($video)
    {
        $statistics = $video->getStatistics();

        $data = [
            'ttt' => 2,
            'object' => $statistics
        ];
        return $data;
    }
}