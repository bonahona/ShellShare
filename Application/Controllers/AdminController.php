<?php
require_once('BaseController.php');
class AdminController extends BaseController
{
    public function BeforeAction()
    {
        if(!$this->LoggedIn()){
            return $this->Redirect('/Admin', array('ref' => $this->RequestUri));
        }
    }
}