<?php

namespace App\Http\Controllers;

class ApiListController extends Controller
{
    public function index()
    {
        return view('admin.api_list.index');
    }
}
