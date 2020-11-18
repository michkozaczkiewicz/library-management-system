<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/author", name="author.")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(AuthorRepository $authorRepository, PaginatorInterface $paginator, Request $request){
        //Show genre list
        $allAuthors = $authorRepository->findAll();

        $authors = $paginator->paginate(
            $allAuthors, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('author/index.html.twig', [
            'authors' => $authors
        ]);
    }
    
    /**
     * @Route("/createAuthor", name="createAuthor")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        //crete a new author
        $author = new Author();
        

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirect($this->generateUrl('author.index'));
        }
      
        return $this->render('author/create.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
    
      /**
     * @Route("/edit/{id}", name="editAuthor")
     * @param Request $request
     * @return Response
     * Method({"PUT"})
     */
    public function edit(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        if (!$author) {
            throw $this->createNotFoundException(
            'There are no author with the following id: ' . $id
            );
        }
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $author = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('author.index'));
        }
        //return a response
        return $this->render('author/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/deleteAuthor/{id}", name="deleteAuthor")
     * @param Author $author
     * @return Response
     */
    public function deleteAuthor(Author $author){
        $em = $this->getDoctrine()->getManager();
        $em->remove($author);
        $em->flush();
        $this->addFlash('succes', 'Author was removed');
        return $this->redirect($this->generateUrl('author.index'));
    }
    


}
