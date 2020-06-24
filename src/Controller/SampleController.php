<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class SampleController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="main_page")
     */
    public function content() {

        $array = ['one', 'two', 'three'];

        dump($array);

        return $this->render(
            'content/content.html.twig',
            [
                'message' => 'Hello',
                'array' => $array
            ]
        );
    }

    /**
     * @Route("/home", methods={"GET"})
     */
    public function home() {
        return $this->render(
            'dir/example.html.twig',
            [
                'message' => 'Hello',
                'array' => [
                    'one', 'two', 'three'
                ]
            ]
        );
    }

    /**
     * @Route("/page", methods={"GET"})
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
