<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Contact;
use App\Entity\Painting;
use App\Form\CommentType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\PaintingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function gallery(Request $request, PaintingRepository $paintingRepository)
    {
        return $this->render('gallery.html.twig', array(
            'paintings' => $paintingRepository->findOrderByYear(),
        ));
    }

    /**
     * @Route("/mes-oeuvres/{id}", methods={"GET","POST"}, name="gallery_show")
     */
    public function painting(Request $request, Painting $painting): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $painting->addComment($comment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', "Votre commentaire est bien enregistré");
        }

        return $this->render('painting.html.twig', [
            'painting' => $painting,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}", name="comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery');
    }

    /**
     * @Route("/a-propos-de-moi", name="about")
     */
    public function about()
    {
        return $this->render('about.html.twig');
    }

    /**
     * @Route("/mon-atelier", name="workshop")
     */
    public function workshop()
    {
        return $this->render('workshop.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param ContactNotification $notification
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function contact(Request $request, ContactNotification $notification)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->render('contact.html.twig', [
                'form' => $form->createView()
            ]);
        }

        return $this->render('contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/politique-de-confidentialité", name="confidentiality")
     */
    public function confidentiality()
    {
        return $this->render('confidentiality.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legals")
     */
    public function legals()
    {
        return $this->render('legals.html.twig');
    }
}
