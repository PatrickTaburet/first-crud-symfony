<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticleType;
use App\Repository\ArticlesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(ArticlesRepository $repo): Response
    {
        $datas = $repo-> findAll();

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'datas'=> $datas
        ]);
    }

    /**
    * @Route("/create", name="create", methods= {"GET", "POST"})
    */
    public function Create(Request $request): Response
    {
        $article = new Articles(); //Entity name
        $form = $this->createForm(ArticleType::class, $article); // creation du form
        $form -> handleRequest($request);  // Gestion des données envoyées
        if ( $form->isSubmitted() && $form->isValid()){
            $sendDatabase = $this->getDoctrine()
                                 ->getManager();
            $sendDatabase->persist($article);
            $sendDatabase->flush();

            $this->addFlash('notice', 'Soumission réussie !!'); 

            return $this->redirectToRoute('main');
        }
        return $this->render('main/creationForm.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView()
        ]);
    }
}
