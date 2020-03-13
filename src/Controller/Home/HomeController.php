<?php

namespace App\Controller\Home;

use App\Entity\Admin\Messages;
use App\Entity\Admin\Product;
use App\Form\Admin\MessagesType;
use App\Repository\Admin\CommentRepository;
use App\Repository\Admin\ImageRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, SettingRepository $settingRepository)
    {
        $slider = $productRepository->findBy([], ['title' => 'ASC'], 6);
        $allproduct = $productRepository->findBy([], ['title' => 'ASC']);
        $newproduct = $productRepository->findBy([], ['title' => 'ASC'], 9);
        $data = $settingRepository->findBy(['id' => 1]);


        return $this->render('home/index.html.twig', [
            'slider' => $slider,
            'allproduct' => $allproduct,
            'newproduct' => $newproduct,
            'data' => $data,
        ]);
    }


    /**
     * @Route("product/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, $id, ImageRepository $imageRepository, CommentRepository $commentRepository): Response
    {

        $images = $imageRepository->findBy(["product" => $id]);
        $comments = $commentRepository->findBy(["productid" => $id, 'status' => 'True']);

        return $this->render('home/singleproduct.html.twig', [
            'product' => $product,
            'image' => $images,
            'comments' => $comments,
        ]);
    }



    /**
     * @Route("aboutus", name="home_about")
     */
    public function aboutus(SettingRepository $settingRepository)
    {
        $setting = $settingRepository->findAll();
        return $this->render('home/aboutus.html.twig', [
            'setting' => $setting,
        ]);
    }

   /**
     * @Route("contact", name="home_contact", methods={"GET","POST"})
     */
    public function contact(SettingRepository $settingRepository, Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessagesType::class, $message);
        $form->handleRequest($request);
        $submittedToken = $request->request->get('token');
        $setting = $settingRepository->findAll();

        if ($form->isSubmitted()) {

            if ($this->isCsrfTokenValid('form-message', $submittedToken)) {
                $entityManager = $this->getDoctrine()->getManager();

                $message->setStatus('New');
                $message->setIp($_SERVER['REMOTE_ADDR']);

                $entityManager->persist($message);
                $entityManager->flush();


                //--------------------Send Email------------------//

                $email = (new Email())

                    ->from($setting[0]->getSmtpemail())
                    ->to($form['email']->getData())
                    ->subject('Time for Symfony Mailer!')
                    //->text('Sending emails is fun again!')
                    ->html(
                        "Dear" . $form['name']->getData() . "<br>
                            <p> We will evalute your requests and contact you as soon as possible</p>
                            Thank you <br>
                            ==========================================================
                            <br>" . $setting[0]->getCompany() . "<br>
                            Address:" . $setting[0]->getCompany() . "<br>
                            Phone:" . $setting[0]->getPhone() . "<br>"
                    );
                $transport = new GmailSmtpTransport($setting[0]->getSmtpemail(), $setting[0]->getSmtppassword());
                $mailer = new Mailer($transport);
                $mailer->send($email);

                //--------------------Send Email------------------//


                return $this->redirectToRoute('home_contact');
            }
        }

        return $this->render('home/contact.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
        ]);
    }
}
