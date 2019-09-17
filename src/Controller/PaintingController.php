<?php

namespace App\Controller;

use App\Entity\Painting;
use App\Form\PaintingType;
use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_USER")
 */
class PaintingController extends AbstractController
{
    /**
     * @Route("/", name="painting_index", methods={"GET"})
     */
    public function index(PaintingRepository $paintingRepository): Response
    {
        return $this->render('painting/index.html.twig', [
            'paintings' => $paintingRepository->findOrderByYear(),
        ]);
    }

    /**
     * @Route("/new", name="painting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $painting = new Painting();
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($painting);
            $entityManager->flush();

            return $this->redirectToRoute('painting_index');
        }

        return $this->render('painting/new.html.twig', [
            'painting' => $painting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="painting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Painting $painting): Response
    {
        $form = $this->createForm(PaintingType::class, $painting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('painting_index');
        }

        return $this->render('painting/edit.html.twig', [
            'painting' => $painting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="painting_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Painting $painting): Response
    {
        if ($this->isCsrfTokenValid('delete'.$painting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($painting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('painting_index');
    }
}
