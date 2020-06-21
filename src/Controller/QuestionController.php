<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class QuestionController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function homepage() {
        $style = '<style>body{background: silver}</style>';
        $html = '<h1 style="text-align: center; padding:50px">Response</h1>';
        return new Response($style . $html);
    }

    /**
     * @Route("/greeting/{name?}", methods={"GET"})
     * @param $name
     * @return Response
     */
    public function greeting($name) {
        if(!$name) $name = 'Default';
        $style = '<style>body{background: silver}</style>';
        $html = '<h1 style="text-align: center; padding:50px">Greetings, %s!</h1>';
        return new Response(sprintf($style . $html, $name));
    }
}
