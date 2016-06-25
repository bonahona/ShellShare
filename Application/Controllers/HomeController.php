<?php

class HomeController extends Controller
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