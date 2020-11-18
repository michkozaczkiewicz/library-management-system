<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/publisher", name="publisher.")
 */
class PublisherController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(PublisherRepository $publisherRepository, PaginatorInterface $paginator, Request $request){
        //Show genre list
        $allPublishers = $publisherRepository->findAll();

        $publishers = $paginator->paginate(
            $allPublishers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('publisher/index.html.twig', [
            'publishers' => $publishers
        ]);
    }
    
    /**
     * @Route("/createPublisher", name="createPublisher")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        //crete a new publisher
        $publisher = new Publisher();
        

        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($publisher);
            $em->flush();
            return $this->redirect($this->generateUrl('publisher.index'));
        }
      
        return $this->render('publisher/create.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
    
      /**
     * @Route("/edit/{id}", name="editPublisher")
     * @param Request $request
     * @return Response
     * Method({"PUT"})
     */
    public function edit(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $publisher = $this->getDoctrine()->getRepository(Publisher::class)->find($id);
        if (!$publisher) {
            throw $this->createNotFoundException(
            'There are no publisher with the following id: ' . $id
            );
        }
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $publisher = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('publisher.index'));
        }
        //return a response
        return $this->render('publisher/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/deletePublisher/{id}", name="deletePublisher")
     * @param Publisher $publisher
     * @return Response
     */
    public function deletePublisher(Publisher $publisher){
        $em = $this->getDoctrine()->getManager();
        $em->remove($publisher);
        $em->flush();
        $this->addFlash('succes', 'Publisher was removed');
        return $this->redirect($this->generateUrl('publisher.index'));
    }
    


}
