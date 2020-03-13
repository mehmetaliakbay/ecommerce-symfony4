<?php

namespace App\Controller\Admin;

use App\Entity\Admin\Category;
use App\Form\Admin\CategoryType;
use App\Repository\Admin\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/category")
 */
class CategoryController extends AbstractController
{  /**
    * @Route("/", name="admin_category_index", methods={"GET"})
    */
   public function index(CategoryRepository $categoryRepository): Response
   {
       return $this->render('admin/category/index.html.twig', [
           'categories' => $categoryRepository->findAll(),
       ]);
   }

   /**
    * @Route("/new", name="admin_category_new", methods={"GET","POST"})
    */
   public function new(Request $request,CategoryRepository $categoryRepository): Response
   {
       $category = new Category();
       $form = $this->createForm(CategoryType::class, $category);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($category); //Create Row for data in database
           $entityManager->flush();            // Save data to database

           return $this->redirectToRoute('admin_category_index');
       }
       $categories = $categoryRepository->findAll();

       return $this->render('admin/category/new.html.twig', [
           'categories' => $categories,
           'category' => $category,
           'form' => $form->createView(),
       ]);
   }

   /**
    * @Route("/{id}", name="admin_category_show", methods={"GET"})
    */
   public function show(Category $category): Response
   {
       return $this->render('admin/category/show.html.twig', [
           'category' => $category,
       ]);
   }

   /**
    * @Route("/{id}/edit", name="admin_category_edit", methods={"GET","POST"})
    */
   public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
   {
       $form = $this->createForm(CategoryType::class, $category);
       $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
           $this->getDoctrine()->getManager()->flush();

           return $this->redirectToRoute('admin_category_index');
       }

       $categories = $categoryRepository->findAll();

       return $this->render('admin/category/edit.html.twig', [
           'categories' => $categories,
           'category' => $category,
           'form' => $form->createView(),
       ]);
   }

   /**
    * @Route("/{id}", name="admin_category_delete", methods={"DELETE"})
    */
   public function delete(Request $request, Category $category): Response
   {
       if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->remove($category);
           $entityManager->flush();
       }

       return $this->redirectToRoute('admin_category_index');
   }
}
