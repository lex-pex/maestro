<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class QuestionController
{
    public function homepage() {
        $style = '<style>body{background: silver}</style>';
        $html = '<h1 style="text-align: center; padding:50px">Response</h1>';
        return new Response($style . $html);
    }
}
