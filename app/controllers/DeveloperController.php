<?php

class DeveloperController extends BaseController {

    protected $layout = 'master';

    public function index()
    {
        $data = array();
        return View::make('developer/info')->with($data);
    }


}