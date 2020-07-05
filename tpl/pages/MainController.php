<?php

namespace Pages;

use \App\Pagesystem\Controller;
use \App\Pagesystem\Request;

class MainController extends Controller {

    public function Get(Request $request, $page, $params)
    {
        return tpl('main')->AddParams(['dd' => 33]);
    }
    
    public function Post(Request $request, $page, $params)
    {
        return true;
    }

}