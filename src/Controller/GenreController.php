<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/genre", name="genre.")
 */
class GenreController extends AbstractController 
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(GenreRepository $genreRepository, PaginatorInterface $paginator, Request $request){
        //Show genre list
        $allGenres = $genreRepository->findAll();

        $genres = $paginator->paginate(
            $allGenres, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('genre/index.html.twig', [
            'genres' => $genres
        ]);
    }
    
    /**
     * @Route("/createGenre", name="createGenre")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        //crete a new genre
        $genre = new Genre();
        

        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($genre);
            $em->flush();
            return $this->redirect($this->generateUrl('genre.index'));
        }
      
        return $this->render('genre/create.html.twig', [
            'form' => $form->createView()
        ]);
       
    }
    
      /**
     * @Route("/edit/{id}", name="editGenre")
     * @param Request $request
     * @return Response
     * Method({"PUT"})
     */
    public function edit(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $genre = $this->getDoctrine()->getRepository(Genre::class)->find($id);
        if (!$genre) {
            throw $this->createNotFoundException(
            'There are no genre with the following id: ' . $id
            );
        }
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $genre = $form->getData();
            $em->flush();
            return $this->redirect($this->generateUrl('genre.index'));
        }
        //return a response
        return $this->render('genre/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/deleteGenre/{id}", name="deleteGenre")
     * @param Genre $genre
     * @return Response
     */
    public function deleteGenre(Genre $genre){
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();
        $this->addFlash('succes', 'Genre was removed');
        return $this->redirect($this->generateUrl('genre.index'));
    }
    


}
