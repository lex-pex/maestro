<?php

namespace App\Assist;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class Redirect
 * @package App\Assist
 */
class Redirect
{
    /**
     * @var Environment - template for response page
     */
    private $twig;

    public function __construct(Environment $twig) {
        $this->twig = $twig;
    }

    /**
     * Redirect to a corresponding error page
     * @param int $code
     * @return Response | null
     */
    public function abort(int $code) {
        if($code === 404) {
            $html = $this->twig->render('errors/404.html.twig', [
                'title' => 'Page not found'
            ]);
            return new Response($html, $code);
        }
        return null;
    }
}
