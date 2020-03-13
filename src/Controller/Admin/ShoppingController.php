<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Shopping;
use App\Form\Admin\ShoppingType;
use App\Repository\Admin\ShoppingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/shopping")
 */
class ShoppingController extends AbstractController
{
    /**
     * @Route("/{slug}", name="admin_shopping_index", methods={"GET"})
     */
    public function index($slug,ShoppingRepository $shoppingRepository): Response
    {
        $shoppings = $shoppingRepository->getShoppings($slug);
        return $this->render('admin/shopping/index.html.twig', [
            'shoppings' => $shoppings,
        ]);
    }

    /**
     * @Route("/new", name="admin_shopping_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $shopping = new Shopping();
        $form = $this->createForm(ShoppingType::class, $shopping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($shopping);
            $entityManager->flush();

            return $this->redirectToRoute('admin_shopping_index');
        }

        return $this->render('admin/shopping/new.html.twig', [
            'shopping' => $shopping,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_shopping_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show($id,ShoppingRepository $shoppingRepository): Response
    {
        $shopping = $shoppingRepository->getShopping($id);
        return $this->render('admin/shopping/show.html.twig', [
            'shopping' => $shopping,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_shopping_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Shopping $shopping): Response
    {
        $form = $this->createForm(ShoppingType::class, $shopping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $status = $form['status']->getData();
            return $this->redirectToRoute('admin_shopping_index',['slug'=>$status]);
        }

        return $this->render('admin/shopping/edit.html.twig', [
            'shopping' => $shopping,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_shopping_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Shopping $shopping): Response
    {
        if ($this->isCsrfTokenValid('delete'.$shopping->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($shopping);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_shopping_index');
    }
}
