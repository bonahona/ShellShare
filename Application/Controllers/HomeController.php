<?php

class HomeController extends Controller
{
    public function Index()
    {
        $this->Logging->Write("This is a test");
        $this->Logging->Cache->Write("This should also end up in cachelog");
        $this->Logging->FileLog->Write("This should go in the filelog");
        $this->Logging->Db->Write("This should end up in th DB");

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