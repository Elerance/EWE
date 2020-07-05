<?php

namespace Pages;

use \App\Pagesystem\Controller;
use \App\Pagesystem\Request;

class _404Controller extends Controller {

    public function Get(Request $request, $page, $params)
    {
        return tpl('404');
    }
    
    public function Post(Request $request, $page, $params)
    {
        return true;
    }

}