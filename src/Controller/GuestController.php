<?php

namespace App\Controller;

use App\Assist\Pager;
use App\Assist\Redirect;
use App\Entity\Categories;
use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GuestController
 * open to web resources
 * @package App\Controller
 */
class GuestController extends AbstractController
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Response
     * @Route("/", methods={"get"}, name="main")
     */
    public function index(Request $request)
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Items::class);
        $p = $request->get('page');
        $page = ($p && is_numeric($p)) ? abs($p) : 1;
        $limit = 6;
        $offset = $limit * ($page - 1);
        $total = count($repository->findBy([], []));
        $items = $repository->findBy([], ['id'=>'desc'], $limit, $offset);
        return $this->render('guest/index.html.twig', [
            'categories' => Categories::getArray($doctrine),
            'category_id' => 1,
            'items' => $items,
            'pager' => Pager::widget($total, $limit, $page, '/'),
            'title' => 'Main Items List',
        ]);
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Request $request
     * @param Redirect $redirect
     * @return Response
     * @Route("/category/{alias}", methods={"get"}, name="category")
     */
    public function category($alias, Request $request, Redirect $redirect)
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Categories::class);
        $category = $repository->findOneBy(['alias' => $alias]);
        if(!$category) return $redirect->abort(404);
        if($category->getId() == 1) return $this->redirect('/');
        $repository = $doctrine->getRepository(Items::class);
        $p = $request->get('page');
        $page = ($p && is_numeric($p)) ? abs($p) : 1;
        $limit = 6;
        $offset = $limit * ($page - 1);
        $total = count($repository->findBy(['categoryId' => $category->getId()], []));
        $items = $repository->findBy(['categoryId' => $category->getId()], ['id'=>'desc'], $limit, $offset);
        return $this->render('guest/index.html.twig', [
            'categories' => Categories::getArray($doctrine),
            'category_id' => $category->getId(),
            'items' => $items,
            'pager' => Pager::widget($total, $limit, $page),
            'title' => 'Category: ' . $category->getName()
        ]);
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Redirect $redirect
     * @return Response
     * @Route("/item/{alias}", methods={"get"}, name="item")
     */
    public function item($alias, Redirect $redirect)
    {
        $doctrine = $this->getDoctrine();
        if(!$item = $doctrine->getRepository(Items::class)->findOneBy(['alias' => $alias]))
            return $redirect->abort(404);
        return $this->render(
            'guest/item.html.twig', [
            'item' => $item,
            'categories' => Categories::getArray($doctrine),
            'category_id' => $item->getCategoryId(),
            'title' => $item->getTitle()
        ]);
    }
}


