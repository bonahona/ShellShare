<?php
class AdminController extends Controller
{
    public function BeforeAction()
    {
        if(!$this->LoggedIn()){
            return $this->Redirect('/Admin', array('ref' => $this->RequestUri));
        }
    }
}