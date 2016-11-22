<?php
class TestController extends Controller
{
    public function TestNothing()
    {
        return null;
    }

    public function TestString()
    {
        return "This is a test";
    }

    public function TestJson()
    {
        $testData = array(1, 2, 3, 4, 5, 6);

        return $this->Json($testData);
    }

    public function TestRedirect()
    {
        return $this->Redirect('/Test/TestJson');
    }

    public function TestText()
    {
        return $this->Text('This is a string');
    }

    public function TestView()
    {
        $this->Title = 'This is a test';
        return $this->View();
    }
}