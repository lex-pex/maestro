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
    public function abort(int $code, string $twig_path, array $message) {
        if($code === 404) {
            $html = $this->twig->render('errors/404.html.twig', [
                'title' => 'Page not found'
            ]);
            return new Response($html, $code);
        }
        if($code === 302) {
            $html = $this->twig->render($twig_path, $message);
            return new Response($html, $code);
        }
        return null;
    }
}
