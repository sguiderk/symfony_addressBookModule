<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\PersonType;
use AppBundle\Entity\Person;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use AppBundle\Services\FileUploader;

class IndexController extends Controller
{
    /**
     * 
     * @Route("/",name="contact_list")
     */
    public function indexAction()
    {
        return $this->render('index/index.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/new",name="contact_new")
     */
    public function addAction(ObjectManager $manager,Request $request,FileUploader $uploader)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class,$person);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            //picture file process
            if($person->getPicture())
            {
               $filename =  $uploader->uploadfile($person->getPicture());
               if($filename) $person->setPicture($filename);
               else
                   $this->addFlash("warning", "Oops ! An error has accured while saving the picture.");
            }
            
            $manager->persist($person);
            $manager->flush();
            
            $this->addFlash("success", "New contact <strong>{$person->getFullname()} </strong> is saved !");
            
            return $this->redirectToRoute("contact_list",[]);
        }
        
        return $this->render('index/add.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}")
     * @param ObjectManager $manager
     * @param Person $person
     */
    public function editAction(ObjectManager $manager,Person $person,Request $request,FileUploader $uploader)
    {
        $form = $this->createForm(PersonType::class,$person);
        $last_picture = $person->getPicture();
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            //picture file process
            if($person->getPicture())
            {
                $filename =  $uploader->uploadfile($person->getPicture());
                if($filename) $person->setPicture($filename);
                else
                    $this->addFlash("warning", "Oops ! An error has accured while saving the picture.");
            }else{
                $person->setPicture($last_picture);
            }
            
            
            $manager->persist($person);
            $manager->flush();
            
            $this->addFlash("success", "Contact <strong>{$person->getFullname()} </strong> is modified with success !");
            
            return $this->redirectToRoute("contact_list",[]);
        }
        return $this->render("index/edit.html.twig",
            [
                'form'=> $form->createView(),
                "contact"=> $person
            ]);
    }
}
