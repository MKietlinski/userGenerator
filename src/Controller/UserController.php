<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\UserType;
use App\Service\FormErrorsFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route(path="/")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Użytkownik poprawnie dodany');
            $form = $this->createForm(UserType::class);
        }

        return $this->render('User/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route(path="/api")
     */
    public function createApi(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(UserType::class);
        $form->submit($data);

        if (!$form->isValid()) {
            return new JsonResponse(['errors' => FormErrorsFormatter::format($form)], Response::HTTP_BAD_REQUEST);
        }

        $user = $form->getData();
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse();
    }
}