<?php

/*
 *   App Core Class
 *   Creates URL & loads core controller
 *   URL FORMAT - /controller/method/params
 */

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        //look in controllers for first value
        if (!empty($url) && file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {

            //if exists,  set as  controller
            $this->currentController = ucwords($url[0]);
            //unset 0 index
            unset($url[0]);
        }
        //require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';
        //instantiate controller class
        $this->currentController = new $this->currentController;

        if (isset($url[1])) {
            //check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {

                $this->currentMethod = $url[1];
                //unset 1 index
                unset($url[1]);
            }
        }
        //get params
        $this->params = $url ? array_values($url) : [];

        //call a callback with array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {

            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;

        }
    }
}
