<?php

namespace App\Controller;

use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FrontEndController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/mes-oeuvres", name="gallery")
     */
    public function gallery(PaintingRepository $paintingRepository)
    {
        return $this->render('gallery.html.twig', [
            'paintings' => $paintingRepository->findOrderByYear(),
        ]);
    }

    /**
     * @Route("/a-propos-de-moi", name="about")
     */
    public function about()
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legals")
     */
    public function legals()
    {
        return $this->render('legals.html.twig');
    }

    /**
     * @Route("/politique-de-confidentialité", name="confidentiality")
     */
    public function confidentiality()
    {
        return $this->render('confidentiality.html.twig');
    }
}
