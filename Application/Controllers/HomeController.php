<?php
require_once('BaseController.php');
class HomeController extends BaseController
{
    public function Index()
    {
        $this->Title = "Index";
        $this->View();
    }

    public function NotFound()
    {
        return $this->View();
    }

    public function Search()
    {

    }
}