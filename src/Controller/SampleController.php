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
     * @Route("/api/{id<\d+>}/count/{sign<plus|minus>}", methods={"post"}, name="json_api_post")
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
     * @Route("/json_api", methods={"GET"}, name="json_api_main")
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
     * @Route("/", methods={"GET"}, name="main_page")
     */
    public function home() {
        $message =
            'Aggregation is a weak type of Association ' .
            'with partial ownership. For an Aggregation ' .
            'relationship, we use the term "uses" to ' .
            'imply a weak "has-a" relationship.';
        $router = $this->container->get('router');
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection = $router->getRouteCollection();
        $allRoutes = $collection->all();
        dump($allRoutes);
        $routes = array();
        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params)
        {
            $defaults = $params->getDefaults();
            if (isset($defaults['_controller']))
            {
                $controllerAction = explode(':', $defaults['_controller']);
                $controller = $controllerAction[0];
                if (!isset($routes[$controller])) {
                    $routes[$controller] = array();
                }
                $routes[$controller][]= $route;
            }
        }
        $thisRoutes = isset($routes[get_class($this)]) ?
            $routes[get_class($this)] : null ;
        return $this->render(
            'main/home.html.twig', [
                'routes' => $thisRoutes,
                'message' => $message,
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
        $message =
            'Aggregation is a weak type of Association ' .
            'with partial ownership. For an Aggregation ' .
            'relationship, we use the term "uses" to ' .
            'imply a weak "has-a" relationship.';
        $html = $twig->render(
            'main/sample.html.twig', [
                'message' => $message,
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
