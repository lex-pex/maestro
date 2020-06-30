<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Items;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class GuestController extends AbstractController
{
    /**
     * Display a listing of the resource.
     * @return Response
     * @Route("/", methods={"get"}, name="main")
     */
    public function index()
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine
            ->getRepository(Items::class);
        $items = $repository->findBy([], ['id'=>'desc'], 6, 0);
        return $this->render(
            'index.html.twig', [
                'categories' => self::getCategories($doctrine),
                'category_id' => 1,
                'items' => $items,
                'title' => 'List of Items'
            ]
        );
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Environment $twig
     * @return Response
     * @Route("/category/{alias}", methods={"get"}, name="category")
     */
    public function category($alias, Environment $twig)
    {
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Categories::class);
        $resultSet = $repository->findBy(['alias' => $alias]);

        if(count($resultSet) < 1) {
            return new Response('page not found', 404);
        } else {
            $category = $resultSet[0];
        }
        /**
         * Main category #1 by default doesn't have any own items
         */
        if($category->getId() == 1) {
            return $this->redirect('/');
        }
        $categories = self::getCategories($doctrine);
        $repository = $doctrine->getRepository(Items::class);
        $items = $repository->findBy(['categoryId' => $category->getId()], ['id'=>'desc'], 6, 0);

        return $this->render(
            'index.html.twig', [
            'items' => $items,
            'categories' => $categories,
            'category_id' => $category->getId(),
            'title' => 'Category: ' . $category->getName()
        ]);
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Environment $twig
     * @return Response
     * @Route("/item/{alias}", methods={"get"}, name="item")
     */
    public function item($alias, Environment $twig)
    {
        $doctrine = $this->getDoctrine();
        $resultSet = $doctrine
            ->getRepository(Items::class)
            ->findBy(['alias' => $alias]);
        if(count($resultSet) == 0) {
            return new Response('page not found', 404);
        } else {
            $item = $resultSet[0];
        }
        $categories = self::getCategories($doctrine);
        return $this->render(
            'items/show.html.twig', [
            'item' => $item,
            'categories' => $categories,
            'category_id' => $item->getCategoryId(),
            'title' => 'Show Post'
        ]);
    }

    /**
     * Get Categories ordered by ids
     * @param $doctrine
     * @return array [ id = {id, alias, name} ] where index == id
     */
    private static function getCategories($doctrine) {
        $cats = $doctrine
            ->getRepository(Categories::class)
            ->findBy([], ['id'=>'asc']);
        $categories = [];
        for($i = 0; $i < count($cats); $i ++) {
            $cat = new \stdClass();
            $cat->id = $cats[$i]->getId();
            $cat->alias = $cats[$i]->getAlias();
            $cat->name = $cats[$i]->getName();
            $categories[$cats[$i]->getId()] = $cat;
        }
        return $categories;
    }
}


