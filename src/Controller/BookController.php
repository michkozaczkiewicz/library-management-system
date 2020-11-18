<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/book", name="book.")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(BookRepository $bookRepository, PaginatorInterface $paginator, Request $request){
        //Show genre list
        $allBooks = $bookRepository->findAll();

        $books = $paginator->paginate(
            $allBooks, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('book/index.html.twig', [
            'books' => $books
        ]);
    }
    
    /**
     * @Route("/createBook", name="createBook")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        //crete a new book
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirect($this->generateUrl('book.index'));
        }
      
        return $this->render('book/create.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
    
      /**
     * @Route("/edit/{id}", name="editBook")
     * @param Request $request
     * @return Response
     * Method({"PUT"})
     */
    public function edit(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $book = $this->getDoctrine()->getRepository(Book::class)->find($id);
        if (!$book) {
            throw $this->createNotFoundException(
            'There are no book with the following id: ' . $id
            );
        }
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('book.index'));
        }
        //return a response
        return $this->render('book/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/deleteBook/{id}", name="deleteBook")
     * @param Book $book
     * @return Response
     */
    public function deleteBook(Book $book){
        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();
        $this->addFlash('succes', 'Book was removed');
        return $this->redirect($this->generateUrl('book.index'));
    }
    


}
