<?php

namespace app\controller;


use app\BaseController;

class IndexController extends BaseController
{
    public function index()
    {

        return view('index');
    }

    public function test(): string
    {
        return "test";
    }
}