<?php

class Zhupanyn_Youtube_Helper_YouTube_Regex
{
    /**
     * Паттерн для получения id_youtube
     */
    const PATTERN_ID_YOUTUBE = '/(?:v=)([\w\-]+)(\&.+)?/';
    /**
     * Результат проверки регулярного выражения
     *
     * @var array
     */
    protected $matches;

    /**
     * Получить ID видео с ссылки на видео
     *
     * @param string $link
     * @return string
     */
    public function getIdYoutubeByLink($link)
    {
        try {
            $regex = new Zend_Validate_Regex(self::PATTERN_ID_YOUTUBE);

            if ($regex->isValid($link)) {
                preg_match($regex->getPattern(), $link,$this->matches);
                $id = $this->getMatch(1);
            } else {
                $id = '';
            }
        } catch (Zend_Validate_Exception $e) {
            $id = '';
        }
        return $id;
    }

    /**
     * @param integer $position
     * @return string
     */
    protected function getMatch($position)
    {
        if (empty($this->matches[$position])) {
            $match = '';
        } else {
            $match = $this->matches[$position];
        }
        return $match;
    }
}