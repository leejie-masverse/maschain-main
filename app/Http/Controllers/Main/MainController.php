<?php


namespace App\Http\Controllers\Main;


use App\Http\Controllers\AuthedController;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    /**
     * Home page
     */
    public function home()
    {
        $ogTitle = '';
        $ogDescription = '';

        return view('main.home',compact('ogTitle','ogDescription'));
    }
}
