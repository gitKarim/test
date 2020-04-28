<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Test\Constraint\RequestAttributeValueSame;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController

{

    /**
     * @Route("/user/register" , name="user_register")
     * @param EntityManagerInterface $manager
     * @param Request $request
     */
    public function register(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User;

        $form = $this->CreateForm(RegisterType::class, $user)->HandleRequest($request);
        $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
        if ($form->isSubmitted() and $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('user/register.html.twig',
            [
                'registerform' => $form->createView()
            ]);

    }
}