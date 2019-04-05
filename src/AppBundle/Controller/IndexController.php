<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\PersonType;
use AppBundle\Entity\Person;

class IndexController extends Controller
{
    /**
     * @Route("/list")
     */
    public function indexAction()
    {
        return $this->render('index/index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/new")
     */
    public function addAction()
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class,$person);
        
        if($form->isSubmitted() && $form->isValid())
        {
            
        }
        
        return $this->render('index/add.html.twig', array(
            'form'=> $form->createView()
        ));
    }

}
