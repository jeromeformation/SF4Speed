<?php

namespace App\Controller;

use App\Entity\Tuto;
use App\Form\TutoType;
use App\Repository\TutoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tuto")
 */
class TutoController extends AbstractController
{
    /**
     * @Route("/", name="tuto_index", methods={"GET"})
     */
    public function index(TutoRepository $tutoRepository): Response
    {
        return $this->render('tuto/index.html.twig', [
            'tutos' => $tutoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tuto_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tuto = new Tuto();
        $tuto->setSlug('ffff');
        $form = $this->createForm(TutoType::class, $tuto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tuto);
            $entityManager->flush();

            return $this->redirectToRoute('tuto_index');
        }

        return $this->render('tuto/new.html.twig', [
            'tuto' => $tuto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="tuto_show", methods={"GET"})
     */
    public function show(Tuto $tuto): Response
    {
        return $this->render('tuto/show.html.twig', [
            'tuto' => $tuto,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="tuto_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tuto $tuto): Response
    {
        $form = $this->createForm(TutoType::class, $tuto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tuto_index', [
                'id' => $tuto->getId(),
            ]);
        }

        return $this->render('tuto/edit.html.twig', [
            'tuto' => $tuto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="tuto_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tuto $tuto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tuto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tuto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tuto_index');
    }
}
