<?php

namespace App\Controller;

use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class GalleryController extends AbstractController
{
    /**
     * @Route("/mes-oeuvres", name="gallery")
     */
    public function index(PaintingRepository $paintingRepository)
    {

        return $this->render('gallery.html.twig', [
            'paintings' => $paintingRepository->findOrderByYear(),
        ]);
    }
}
