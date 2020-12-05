<?php

namespace App\Controller;

use TeraBlaze\Controller\Controller;
use TeraBlaze\HttpBase\Request;
use TeraBlaze\HttpBase\Response;

class WelcomeController extends Controller
{
    public function index(Request $request): Response
    {
        $data['pageTitle'] = 'Welcome to TeraBlaze';
        $data['url'] = $request->getUri()->__toString();

        return $this->render('App::welcome', $data);
    }
}