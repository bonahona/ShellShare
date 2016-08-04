<?php
class BaseController extends Controller
{
    public function BeforeAction()
    {
        $rootDirectories = $this->Models->VirtualDirectory->Where(array('ParentDirectoryId' => null));
        $this->Set('RootDirectories', $rootDirectories);
    }
}
