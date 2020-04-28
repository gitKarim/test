<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class IndexController extends AbstractController
{

    /**
     * @Route("/" , name="index")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CommentRepository $repository
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function index(Request $request, EntityManagerInterface $manager, CommentRepository $repository , UserPasswordEncoderInterface $encoder)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Comment::class);
        $comments = $repository->FindBy([], ['createdAt' => 'DESC'], 6);


        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = new User;
            $user->setEmail('coucou@com.com');
            $user->setNom('coucou');
            $user->setPrenom('tata');
            $user->setPassword($encoder->encodePassword($user , 'password'));
            $manager->persist($user);

            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($user);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('index');


        }


        return $this->render('index.html.twig', [
            'formcomment' => $form->createView(),
            'comments' => $comments
        ]);
    }


    /**
     * @Route("/edit/{id}" , name="edit_comment")
     * @param Comment $comment
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function edit(Comment $comment, Request $request, EntityManagerInterface $manager): ?Response
    {
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            return $this->redirectToRoute('index');
        }


        return $this->render('edit.html.twig', [
            'form' => $form->createView(),
            'id' => $comment->getId()
        ]);


    }

    /**
     * @Route("/delete/{id}" , name="delete_comment")
     * @return Response
     */

    public function delete(Comment $comment, Request $request, EntityManagerInterface $manager)
    {
        $manager->remove($comment);
        $manager->flush();

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/read/{id}" , name="read_comment")
     * @param Comment $comment
     * @return Response
     */
    public function read(Comment $comment  )
    {

        $result = $this->getDoctrine()->getManager()->getRepository(Comment::class)->find($comment->getId());

        return $this->render('read.html.twig' ,
        [
            'id'=> $comment->getId() ,
            'result'=> $result
        ]);

    }
}





