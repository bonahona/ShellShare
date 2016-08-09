<?php
interface IPageCache
{
    public function CachePage($request, $data);
    public function IsCached($request);
    public function GetCache($request);
    public function Invalidate($request);
}