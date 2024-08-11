<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function convertUrlMedia($url){
        $normalizedPath = ltrim(parse_url($url, PHP_URL_PATH));
        return $normalizedPath;
    }

}
