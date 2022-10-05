<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_created",methods={"POST"})
     */
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, NotifierInterface $notifier): Response
    {
        $form = $this->createForm(UserType::class, null, ["method" => "POST"]);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $notification = new Notification('Utilisateur ' . $user->getUsername() . ' créé', ['chat/slack']);
            $notifier->send($notification);

            $email = (new Email())
                ->from('test@test.com')
                ->to($user->getUsername())
                ->subject('Utilisateur créé')
                ->text('Utilisateur ' . $user->getUsername() . ' créé');
            $mailer->send($email);

            return new Response('', Response::HTTP_CREATED);
        } else {
            $errors = [];
            foreach ($form->all() as $field) {
                $fieldKey = $field->getName();
                foreach ($field->getErrors(true) as $error) {
                    if (array_key_exists($fieldKey, $errors)) {
                        $errors[$fieldKey][] = $error->getMessage();
                    } else {
                        $errors[$fieldKey] = [$error->getMessage()];
                    }
                }
            }
            return new Response(json_encode($errors), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @Route("/me", name="user_me",methods={"GET"})
     */
    public function showUserMe()
    {
        $data = $this->get('serializer')->serialize($this->getUser(), 'json', ['groups' => 'detail']);
        $reponse = new Response($data);
        $reponse->headers->set("Content-type", "application/json");

        return $reponse;
    }
}
