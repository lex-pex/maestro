<?php

namespace App\Controller;

use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GuestController extends AbstractController
{
    public function index() {

        $doctrine = $this->getDoctrine();
        $repository = $doctrine
            ->getRepository(Items::class);

        $items = $repository->findBy([], ['id'=>'desc'], 6, 0);

        return $this->render(
            'index.html.twig', [
                'items' => $items
            ]
        );
    }
}


