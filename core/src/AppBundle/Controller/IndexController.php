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
     * desc index action
     * @param ObjectManager $manager
     * @param Request $request
     * @return mixed
     * @Route("/",name="contact_list")
     */
    public function indexAction(ObjectManager $manager, Request $request)
    {
        $repoPerson = $manager->getRepository("AppBundle:Person");
        $query = $repoPerson->getPaginationQuery();
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            5
            );
        $pagination->setCustomParameters(array(
            'align' => 'center',
            'size' => 'small',
            'style' => 'bottom',
        ));

        return $this->render('Index/index.html.twig', array(
            "pagination"=>$pagination,
            "url_pictures"=>$this->getParameter("pictures_folder")
        ));
    }

    /**
     * desc add action
     * @param ObjectManager $manager
     * @param Request $request
     * @param FileUploader $uploader
     * @return mixed
     * @Route("/new",name="contact_new")
     */
    public function addAction(ObjectManager $manager, Request $request, FileUploader $uploader)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class,$person);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            //picture file process
            if ($person->getPicture()) {
               $filename =  $uploader->uploadfile($person->getPicture());
               if ($filename) {
                   $person->setPicture($filename);
               } else {
                   $this->addFlash("warning", "Oops ! An error has accured while saving the picture.");
               }
            }
   
            $manager->persist($person);
            $manager->flush();         
            $this->addFlash("success", "New contact <strong>{$person->getFullname()} </strong> is saved !");
            
            return $this->redirectToRoute("contact_list",[]);
        }
        
        return $this->render('Index/add.html.twig', array(
            'form'=> $form->createView()
        ));
    }

    /**
     * desc delete action
     * @param Person $contact
     * @param ObjectManager $manager
     * @return mixed
     * @Route("/delete/{id}",name="delete_contact")
     */
    public function deleteAction(Person $contact, ObjectManager $manager)
    {
        $manager->remove($contact);
        $manager->flush();
        $this->addFlash("success", "Contact <strong>{$contact->getFullname()}</strong> is removed");
        return $this->redirectToRoute("contact_list");
    }

    /**
     * desc edit action
     * @param ObjectManager $manager
     * @param Person $person
     * @Route("/edit/{id}",name="edit_contact")
     */
    public function editAction(ObjectManager $manager, Person $person, Request $request, FileUploader $uploader)
    {
        $form = $this->createForm(PersonType::class,$person);
        $last_picture = $person->getPicture();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if ($person->getPicture()) {
                $filename =  $uploader->uploadfile($person->getPicture());
                if ($filename) {
                    $person->setPicture($filename);
                } 
                else { 
                    $this->addFlash("warning", "Oops ! An error has accured while saving the picture.");
                }
                  
            } else {
                $person->setPicture($last_picture);
            }
            $manager->persist($person);
            $manager->flush();
            $this->addFlash("success", "Contact <strong>{$person->getFullname()} </strong> is edited with success !");
            
            return $this->redirectToRoute("contact_list",[]);
        }
        return $this->render("Index/edit.html.twig",
            [
                'form'=> $form->createView(),
                "contact"=> $person
            ]);
    }
}
