<?php


namespace App\Controller;

/**
 * Class ItemController
 * closed from web Items resource
 * @package App\Controller
 */
use App\Assist\Redirect;
use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ItemController extends AbstractController
{
    /**
     * Display a listing of the resource.
     * Browsing page of all users.
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items", methods={"get"}, name="items")
     */
    public function index()
    {
        /* if( not admin ) return Redirect::abort('This page is for Admin only') */

        $doctrine = $this->getDoctrine();
        $repository = $doctrine
            ->getRepository(Items::class);
        $items = $repository->findBy([], ['id'=>'desc'], 6, 0);

        return $this->render('items/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Redirect $redirect
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items/{alias}", methods={"get"}, name="items.show")
     */
    public function show($alias, Redirect $redirect)
    {
        /* if( not admin & moderator & author ) return Redirect::abort('This page is for Admin only') */

        $doctrine = $this->getDoctrine();
        $resultSet = $doctrine
            ->getRepository(Items::class)
            ->findBy(['alias' => $alias]);
        if(count($resultSet) == 0) {
            return $redirect->abort(404);
        } else {
            $item = $resultSet[0];
        }
        return $this->render(
            'items/show.html.twig', [
            'item' => $item,
            'title' => 'Show Post'
        ]);
    }
}


