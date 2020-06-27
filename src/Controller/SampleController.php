<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;


class SampleController extends AbstractController
{

    /**
     * Json Api Sample
     * @Route("/api/{id<\d+>}/count/{sign<plus|minus>}", methods={"post"}, name="json_api")
     * @param $id
     * @param $sign
     * @param LoggerInterface $logger
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function jsonApi($id, $sign, LoggerInterface $logger) {

        // todo use id to query database

        if($sign == 'plus') {
            $logger->info('PLUS CLICKED');
            $voteCount = rand(5, 10);
        }
        else {
            $logger->info('MINUS CLICKED');
            $voteCount = rand(1, 5);
        }

        return $this->json(
            [
                'votes' => $voteCount
            ]
        );
    }

    /**
     * @Route("/votes", methods={"GET"}, name="main_page")
     */
    public function votes() {
        $array = ['one', 'two', 'three'];
        dump($array); // goes to the Profiler
        return $this->render('api/votes.html.twig', [
                'message' => 'Hello',
                'array' => $array
            ]
        );
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function home() {
        return $this->render(
            'main/example.html.twig', [
                'message' => 'Hello',
                'array' => ['one', 'two', 'three']
            ]
        );
    }

    /**
     * Service usage sample
     * @Route("/twig", methods={"GET"})
     * @param Environment $twig
     * @return Response
     */
    public function twigService(Environment $twig) {
        $html = $twig->render(
            'main/example.html.twig', [
                'message' => 'Hello',
                'array' => ['one', 'two', 'three']
            ]
        );
        return new Response($html);
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
