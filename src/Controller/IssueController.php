<?php

namespace App\Controller;

use App\Entity\Issue;
use App\Entity\Book;
use App\Entity\User;
use App\Form\IssueType;
use App\Repository\BookRepository;
use App\Repository\IssueRepository;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/issue", name="issue.")
 */
class IssueController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('issue/index.html.twig', [
            'controller_name' => 'IssueController',
        ]);
    }

    /**
     * @Route("/createIssue", name="createIssue")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request){
        //crete a new issue
        $issue = new Issue();
        
        $form = $this->createForm(IssueType::class, $issue);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            
            $quantity = $issue->getBook()->getQuantity();
            dump($quantity);
            $quantity--;
            $quantity = $issue->getBook()->setQuantity($quantity);
            dump($quantity);
            dump($issue);
            $em->persist($issue);
            $em->flush();
           
            
            return $this->redirect($this->generateUrl('issue.createIssue'));
            
        }
      
        return $this->render('issue/createIssue.html.twig', [
            'form' => $form->createView()
        ]);
       
    }

     /**
     * @Route("/showUsersIssue/{id}", name="showUsersIssue")
     * @param Request $request
     * @return Response
     */
    public function showUsersIssue(IssueRepository $issueRepository, PaginatorInterface $paginator, Request $request, $id){
        $result = $issueRepository->findBy(array('user' => $id), array('id'=>'desc'));
        $issues = $paginator->paginate(
            $result, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('issue/show.html.twig', [
            'issues' => $issues
        ]);
    }

     /**
     * @Route("/returnIssue/{id}", name="returnIssue")
     * @param Request $request
     * @return Response
     */
    public function returnIssue($id){
        $em = $this->getDoctrine()->getManager();
        $issue = $em->getRepository(Issue::class)->findOneBy(['id' => $id]);
        $quantity = $issue->getBook()->getQuantity();
        $quantity++;
        $quantity = $issue->getBook()->setQuantity($quantity);
        $issueReturn = $issue->getIssueReturn();
        $dateOfReturn = $issue->getDateOfReturn();
        $dateOfReturn = $issue->setDateOfReturn(new \DateTime());
        $dueDate = $issue->getDueDate();
        if (new \DateTime() < $dueDate) {
            $issueReturn = $issue->setIssueReturn('Returned');
        } else {
            $issueReturn = $issue->setIssueReturn('Late');
        }
        dump($dueDate);
        dump(new \DateTime());
        $em->persist($issue);
        $em->persist($issueReturn);
        $em->persist($dateOfReturn);
        $em->flush();
        
        return $this->redirect($this->generateUrl('showUsers'));
       
    }
    
}
