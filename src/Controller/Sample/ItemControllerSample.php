<?php


namespace App\Controller\Sample;

/**
 * Class ItemController
 * closed from web Items resource
 * @package App\Controller
 */
use App\Assist\Redirect;
use App\Entity\Categories;
use App\Entity\Items;
use App\Entity\Users;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class ItemControllerSample extends AbstractController
{
    /**
     * Display a listing of the resource.
     * Browsing page of all users.
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items_sample", methods={"get"}, name="items_sample")
     */
    public function index()
    {
        /* if( not admin ) return Redirect::abort('This page is for Admin only') */
        $doctrine = $this->getDoctrine();
        $items = $doctrine
            ->getRepository(Items::class)->findBy([], ['id'=>'desc'], 6, 0);
        return $this->render('items/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items_sample/create_prototype", methods={"get"}, name="items_sample.create_prototype")
     */
    public function create_prototype()
    {

//        dd(new DateTime('now'));

        /* if( not admin | moderator | author ) return Redirect::abort('This page is for ${role} only') */
        $mockUser = new \stdClass();
        $mockUser->id = 1;
        $mockUser->name = 'Admin';

        // Form Builder:

        // creates a task object and initializes some data for this example
        $item = new Items();
        $item->setTitle('Create an Item');
        $item->setText('Here is the text part');

        $itemForm = $this->createFormBuilder($item)
            ->setMethod('POST')
            ->setAction($this->generateUrl('items.store'))
            ->add('title', TextType::class)
            ->add('text', TextareaType::class, ['attr' => ['rows' => 5]])
            ->add('save', SubmitType::class, ['label' => 'Create Item'])
            ->getForm();

        // On the template: form( itemForm )

        return $this->render('abort/items/create_prototype.html.twig', [
            'categories' => Categories::allExceptMain($this->getDoctrine()),
            'current_category' => 1,
            'users' => Users::all($this->getDoctrine()), // admin option
            'user' => $mockUser, // current author
            'title' => 'Create Item',
            // Form Builder:
            'form' => $itemForm->createView()
        ]);
    }

    /**
     * Open the form for creating a new resource.
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items_sample/store_prototype", methods={"post"}, name="items_sample.store_prototype")
     */
    public function store() {
        dd('ItemControllerSample::store( post )');
    }

    /**
     * Display the specified resource.
     * @param  string $alias
     * @param Redirect $redirect
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/items_sample/{alias}", methods={"get"}, name="items_sample.show")
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


