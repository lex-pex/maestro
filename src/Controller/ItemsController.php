<?php


namespace App\Controller;

/**
 * Class ItemController
 * closed from web Items resource
 * @package App\Controller
 */
use App\Assist\AliasProcessor;
use App\Assist\ImageProcessor;
use App\Assist\Pager;
use App\Assist\Redirect;
use App\Entity\Items;
use App\Entity\Users;
use App\Form\ItemType;
use App\Form\ItemUpdateType;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ItemsController extends AbstractController
{
    /**
     * @var string path to store this items files
     */
    private $imageStorage = '/img/items/';

    /**
     * Display a listing of the resource.
     * Browsing page of all users.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items", methods={"get"}, name="items")
     */
    public function index(Request $request)
    {
        /* if( not admin ) return Redirect::abort('This page is for Admin only') */
        $repository = $this->getDoctrine()->getRepository(Items::class);
        $p = $request->get('page');
        $page = ($p && is_numeric($p)) ? abs($p) : 1;
        $limit = 6;
        $offset = $limit * ($page - 1);
        $total = count($repository->findBy([], []));
        $items = $repository->findBy([], ['id'=>'desc'], $limit, $offset);
        return $this->render(
            'items/index.html.twig', [
            'items' => $items,
            'pager' => Pager::widget($total, $limit, $page, '/items/'),
            'title' => 'Items List',
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items/create", methods={"get"}, name="items.create")
     */
    public function create()
    {
        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        $moderator = $this->getDoctrine()->getRepository(Users::class)->find(3);
        $item = new Items();
        $item->setUserId($moderator->getId());
        $item->setCategoryId(14); // "Testing Staff" category
        $form = $this->createForm(ItemType::class, $item);
        return $this->render('items/create.html.twig', [
            'form' => $form->createView(),
            'title' => 'Create Item'
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items", methods={"post"}, name="items.store")
     */
    public function store(Request $request) {
        $data = $request->get('item');
        $item = new Items();
        $item->setTitle($data['title']);
        $item->setText($data['text']);
        $item->setCategoryId($data['categoryId']);
        $item->setUserId($data['userId']);
        $item->setCreatedAt(new DateTimeImmutable(date('Y-m-d H:i:s')));
        ImageProcessor::uploadImage($item, $this->imageStorage, $request);
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Items::class);
        if($data['alias'])
            $item->setAlias(AliasProcessor::getAlias($data['alias'], $repository));
        else
            $item->setAlias(AliasProcessor::getAlias($data['title'], $repository));
        $doctrine->getManager()->persist($item);
        $doctrine->getManager()->flush();
        return $this->redirect('/items/' . $item->getAlias());
    }

    /**
     * Open the form for creating a new resource.
     * @param $id
     * @param Redirect $redirect
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items/{id}/edit", methods={"get"}, name="items.edit")
     */
    public function edit($id, Redirect $redirect)
    {
        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        if(!$item = $this->getDoctrine()->getRepository(Items::class)->find($id))
            $redirect->abort(404);
        $form = $this->createForm(ItemUpdateType::class, $item);
        return $this->render('items/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
            'title' => 'Edit Item'
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items", methods={"put"}, name="items.update")
     */
    public function update(Request $request) {
        $data = $request->get('item_update');
        $item = $this->getDoctrine()->getRepository(Items::class)->find($data['id']);
        $item->setTitle($data['title']);
        $item->setText($data['text']);
        $item->setCategoryId($data['categoryId']);
        $item->setUserId($data['userId']);
        $item->setUpdatedAt(new DateTimeImmutable(date('Y-m-d H:i:s')));
        if(isset($data['image_del']))
            ImageProcessor::imageDelete($item);
        ImageProcessor::uploadImage($item, $this->imageStorage, $request);
        $doctrine = $this->getDoctrine();
        $repository = $doctrine->getRepository(Items::class);
        AliasProcessor::aliasUpdate($data['alias'], $data['title'], $item, $repository);
        $doctrine->getManager()->persist($item);
        $doctrine->getManager()->flush();
        return $this->redirect('/items/' . $item->getAlias());
    }

    /**
     * Destroy the resource by id
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param Request $request
     * @Route("/items/{id}", methods={"delete"}, name="items.destroy")
     */
    public function destroy($id)
    {
        $doctrine = $this->getDoctrine();
        $m = $doctrine->getManager();
        $item = $m->find(Items::class, $id);
        ImageProcessor::imageDelete($item);
        $m->remove($item);
        $m->flush();
        return $this->redirect('/items');
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
        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        $doctrine = $this->getDoctrine();
        if(!$item = $doctrine->getRepository(Items::class)->findOneBy(['alias' => $alias]))
            return $redirect->abort(404);
        return $this->render(
            'items/show.html.twig', [
            'item' => $item,
            'title' => 'Show Post'
        ]);
    }
}


