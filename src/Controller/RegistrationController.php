<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

     /**
     * @Route("/showUsers", name="showUsers")
     * @param Request $request
     * @return Response
     */
    public function showUsers(UserRepository $userRepository, PaginatorInterface $paginator, Request $request){
        $role = 'user';
        $usersWithRole = $userRepository->findByRole($role);
        $users = $paginator->paginate(
            $usersWithRole, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

     /**
     * @Route("/showLibrarians", name="showLibrarians")
     * @param Request $request
     * @return Response
     */
    public function showLibrarians(UserRepository $userRepository, PaginatorInterface $paginator, Request $request){
        $role = 'librarian';
        $usersWithRole = $userRepository->findByRole($role);
        $users = $paginator->paginate(
            $usersWithRole, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        return $this->render('user/librarian.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $role = array('ROLE_USER');        
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $data->getPassword()));
            $user->setRoles($role);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            dump($data);
            $em->flush();
            return $this->redirect($this->generateUrl('main'));
        }
        return $this->render('registration/index.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/registerLibrarian", name="registerLibrarian")
     */
    public function registerLibrarian(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User;
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        $role = array('ROLE_LIBRARIAN');        
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $data->getPassword()));
            $user->setRoles($role);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            dump($data);
            $em->flush();
            return $this->redirect($this->generateUrl('main'));
        }
        return $this->render('registration/registerLibrarian.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/user/{id}", name="userShow")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('user/show.html.twig', array('user' => $user));
    }

       /**
     * @Route("/editUser/{id}", name="editUser")
     * @param Request $request
     * @return Response
     * Method({"PUT"})
     */
    public function edit(Request $request, $id, UserPasswordEncoderInterface $passwordEncoder){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException(
            'There are no user with the following id: ' . $id
            );
        }
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $user->setPassword($passwordEncoder->encodePassword($user, $data->getPassword()));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            dump($data);
            $em->flush();
            return $this->redirect($this->generateUrl('main'));
        }
        //return a response
        return $this->render('user/editUser.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
