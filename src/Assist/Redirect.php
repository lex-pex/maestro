<?php

namespace App\Assist;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Redirect
{
    /**
     * @param int $code
     * @param Environment $controller
     * @return Response | null
     */
    public static function abort(int $code, Environment $controller) {
        if($code === 404) {
            $html = $controller->render('errors/404.html.twig', [
                'title' => 'Page not found'
            ]);
            return new Response($html, $code);
        }
        return null;
    }
}
