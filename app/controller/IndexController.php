<?php

namespace app\controller;


use app\BaseController;

class IndexController extends BaseController
{
    public function index(): string
    {
        return "Hello1212";
    }

    public function test(): string
    {
        return "test";
    }
}