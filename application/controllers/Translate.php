<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('myTz', ['controller' => $this]);
    }

    public function index()
    {
        $longUrl = $this->input->post("long_url");
        $shortUrl = null;
        if (! empty($longUrl)) {
            $shortUrl = $this->mytz->getShortUrl($longUrl);
        }
        $this->load->view('translate', ['message' => '', 'shortUrl' => $shortUrl]);
    }

    /**
     * редирект на длинную ссылку (см. config/routes.php)
     * @param $shortUrl
     */
    public function redir($shortUrl)
    {
        $longUrl = $this->mytz->getLongUrl($shortUrl);
        if ( ! empty($longUrl) ) {
            $this->load->helper('url');
            redirect($longUrl);
        }
        $this->load->view('translate', ['message' => 'Короткая ссылка не действительна!', 'shortUrl' => '']);
    }
}
