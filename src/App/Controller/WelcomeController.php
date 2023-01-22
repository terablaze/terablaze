<?php

namespace App\Controller;

use Terablaze\Controller\AbstractController;
use Terablaze\HttpBase\Request;
use Terablaze\HttpBase\Response;

class WelcomeController extends AbstractController
{
    public function index(Request $request): Response
    {
        $data['pageTitle'] = 'Welcome to Terablaze';
        $data['url'] = $request->getUri()->__toString();

        return $this->render('App::welcome', $data);
    }
}