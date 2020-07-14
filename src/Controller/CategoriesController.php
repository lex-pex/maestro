<?php

namespace App\Controller;

use App\Assist\AliasProcessor;
use App\Assist\ImageProcessor;
use App\Assist\Redirect;
use App\Entity\Categories;
use App\Entity\Items;
use App\Form\CategoryType;
use App\Form\CategoryUpdateType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/categories/create", methods={"get"}, name="categories.create")
     */
    public function create()
    {
        /* if( not admin | moderator ) return Redirect::abort('This page is for ${role} only') */
        $category = new Categories();
        $form = $this->createForm(CategoryType::class, $category);
        return $this->render('categories/create.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create Category'
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/categories", methods={"post"}, name="categories.store")
     */
    public function store(Request $request) {
        $data = $request->get('category');
        $category = new Categories();
        $category->setName($data['name']);
        $category->setDescription($data['description']);
        $category->setCreatedAt(new DateTimeImmutable(date('Y-m-d H:i:s')));
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Categories::class);
        if($data['alias'])
            $category->setAlias(AliasProcessor::getAlias($data['alias'], $repository));
        else
            $category->setAlias(AliasProcessor::getAlias($data['name'], $repository));
        ImageProcessor::uploadImage($category, $this->imageStorage, $request);
        $manager = $doctrine->getManager();
        $manager->persist($category);
        $manager->flush();
        return $this->redirect('/categories/' . $category->getAlias());
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
        $category = $this->getDoctrine()->getRepository(Categories::class)->find($data['id']);
        $category->setName($data['name']);
        $category->setDescription($data['description']);
        $category->setUpdatedAt(new DateTimeImmutable(date('Y-m-d H:i:s')));
        if(isset($data['image_del']))
            ImageProcessor::imageDelete($category);
        ImageProcessor::uploadImage($category, $this->imageStorage, $request);
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Categories::class);
        AliasProcessor::aliasUpdate($data['alias'], $data['name'], $category, $repository);
        $doctrine->getManager()->persist($category);
        $doctrine->getManager()->flush();
        return $this->redirect('/categories/' . $category->getAlias());
    }

    /**
     * Destroy the resource by id
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @Route("/categories/{id}", methods={"delete"}, name="categories.destroy")
     */
    public function destroy($id, Redirect $redirect)
    {
        $m = $this->getDoctrine()->getManager();

        $category = $m->find(Categories::class, $id);

        if($m->getRepository(Items::class)->findOneBy(['categoryId' => $id]))
//            return new Response('errors/error.html.twig', 302, ['message' => 'The category is not empty']);
            return $redirect->abort(302, 'errors/error.html.twig', ['message' => 'The category is not empty']);
        ImageProcessor::imageDelete($category);
        $m->remove($category);
        $m->flush();
        return $this->redirect('/categories');
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
