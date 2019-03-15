<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyTz
{
    private $controller = null;

    private $db = null;

    private $hostAddr = null;

    public function __construct($params)
    {
        $this->controller = $params['controller'];
        $this->controller->load->database();
        $this->db = $this->controller->db;


    }


    /**
     * Получение полного имени хоста
     * @return null|string
     */
    public function getHostName()
    {
        // Получаем полное имя хоста
        if ($this->hostAddr === null) {
            $scheme = $this->controller->input->server('REQUEST_SCHEME');
            $name = $this->controller->input->server('SERVER_NAME');
            $port =  $this->controller->input->server('SERVER_PORT');

            if ($scheme == 'http' && $port != "80" || $scheme == 'https' && $port != "443") {
                $name = $name . ':' . $port;
            }

            $this->hostAddr = $scheme . '://' . $name;
        }
        return $this->hostAddr;
    }


    /**
     * Получение короткой ссылки
     * @param $longUrl - длинная ссылка
     * @return string
     */
    public function getShortUrl($longUrl)
    {
        // Ищем длинную ссылку в БД
        $row = $this->db->get_where('urls', ['long_url' => $longUrl])->row();
        if ($row !== null) {
            // Ссылка найдена
            $shortUrl = $row->short_url;
        } else {
            // Ссылка не найдена - добавляем в БД
            $this->db->insert('urls', ['long_url' => $longUrl]);

            // Получаем ID добавленной записи
            $id = $this->db->conn_id->lastInsertId();

            // Генерируем короткую ссылку на основе ID
            $shortUrl = base_convert(50000 + 32 * $id, 10, 32);

            // Записываем короткую ссылку в БД
            $this->db->update('urls', ['short_url' => $shortUrl], ['id' => $id]);
        }


        $shortUrl = $this->getHostName() . '/' . $shortUrl;
        return $shortUrl;
    }

    /**
     * Получение длинной ссылки для редиректа
     * @param $shortUrl - короткая ссылка
     * @return null
     */
    public function getLongUrl($shortUrl)
    {
        $longUrl = null;
        // Ищем короткую ссылку в БД
        $row = $this->db->get_where('urls', ['short_url' => $shortUrl])->row();
        if ($row !== null) {
            // Если найдена, то выводим ее
            $longUrl = $row->long_url;
        }
        return $longUrl;
    }
}