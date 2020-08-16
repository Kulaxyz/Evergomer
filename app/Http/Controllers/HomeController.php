<?php

namespace App\Http\Controllers;

use App\FrontendSetting;
use App\Page;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function mainpage()
    {
        $blocks = Page::where('name', 'mainpage')->first()->blocks;
        $data = json_decode($blocks);
        $gallery_images = json_decode(FrontendSetting::get('gallery_images'));

        return view('main', compact('data', 'gallery_images'));
    }
}
