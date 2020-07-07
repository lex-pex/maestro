<?php

namespace App\Controller;

use App\Assist\ImageProcessor;
use App\Assist\Redirect;
use App\Entity\Categories;
use App\Form\CategoryUpdateType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    /**
     * @var string path to store this items files
     */
    private $imageStorage = '/img/categories/';

    /**
     * @Route("/categories", methods={"get"}, name="categories")
     */
    public function index()
    {
        /* if( not admin ) return Redirect::abort('This page is for Admin only') */
        $doctrine = $this->getDoctrine();
        $categories = $doctrine
            ->getRepository(Categories::class)->findBy([], ['id'=>'desc'], 6, 0);
        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'title' => 'Categories List'
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @param $id
     * @param Redirect $redirect
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/categories/{id}/edit", methods={"get"}, name="categories.edit")
     */
    public function edit($id, Redirect $redirect)
    {
        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        if(!$category = $this->getDoctrine()->getRepository(Categories::class)->find($id))
            $redirect->abort(404);
        $form = $this->createForm(CategoryUpdateType::class, $category);
        return $this->render('categories/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
            'title' => 'Edit Category'
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/categories", methods={"put"}, name="categories.update")
     */
    public function update(Request $request) {
        $data = $request->get('category_update');
        $item = $this->getDoctrine()->getRepository(Categories::class)->find($data['id']);
        $item->setName($data['name']);
        $item->setDescription($data['description']);
        $item->setAlias($data['alias']);
        $item->setUpdatedAt(new DateTimeImmutable(date('Y-m-d H:i:s')));
        if(isset($data['image_del'])) {
            ImageProcessor::imageDelete($item);
            $item->setImage('');
        }
        ImageProcessor::uploadImage($item, $this->imageStorage, $request);
        $doctrine = $this->getDoctrine();
        $doctrine->getManager()->persist($item);
        $doctrine->getManager()->flush();
        return $this->redirect('/categories/' . $item->getAlias());
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Redirect $redirect
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/categories/{alias}", methods={"get"}, name="categories.show")
     */
    public function show($alias, Redirect $redirect)
    {
        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        $doctrine = $this->getDoctrine();
        if(!$category = $doctrine->getRepository(Categories::class)->findOneBy(['alias' => $alias]))
            return $redirect->abort(404);
        return $this->render(
            'categories/show.html.twig', [
            'category' => $category,
            'title' => 'Show Category'
        ]);
    }
}
