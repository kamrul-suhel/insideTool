<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facebook;
use App\Page;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::all();
        return view('pages.index', ['pages' => $pages]);
    }
}
