<?php
namespace App\Controller;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class IndexController extends AbstractController
{
    private $entityManager;
    


    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        
    }

        
    #[Route('/', name: 'article_list')]
    public function home()
    {
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        $articles= $this->entityManager->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig',['articles'=> $articles]);
    }

    #[Route('/articles/create', name: 'new_article',methods: ["POST","GET"])]
    public function new(Request $request,FormFactoryInterface $formFactory)
    {
    $article = new Article();
    $form =  $this->createFormBuilder($article)->add('nom', TextType::class)->add('prix', TextType::class)->add('save', SubmitType::class, array('label' => 'Créer'))->getForm();
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        //$this->entityManager->getDoctrine()->getManager();
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $this->redirectToRoute('article_list');
    }
        return $this->render('articles/new.html.twig',['form' => $form->createView()]);
    }


    #[Route('/article/edit/{id}', name: 'edit_article',methods: ["POST","GET"])]
    public function edit(Request $request, $id,FormFactoryInterface $formFactory) {
        $article = new Article();
        $article = $this->entityManager->getRepository(Article::class)->find($id);
    
        $form = $this->createFormBuilder($article)
        ->add('nom', TextType::class)
        ->add('prix', TextType::class)
        ->add('save', SubmitType::class, array('label' => 'Modifier'))->getForm();
    
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
        $this->entityManager->flush();
    
        return $this->redirectToRoute('article_list');
        }  
        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
    }
        

  
    #[Route('/article/delete/{id}', name: 'delete_article')]
    public function delete(Request $request, $id) {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
    
        //$entityManager = $this->getDoctrine()->getManager();
        $this->entityManager->remove($article);
        $this->entityManager->flush();
    
        $response = new Response();
        $response->send();
        return $this->redirectToRoute('article_list');
    }
   
    
    #[Route('/article/{id}', name: 'article_show')]
    public function show($id) {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        return $this->render('articles/show.html.twig',array('article' => $article));
    }
    


    // #[Route('/article/save', name: 'save')]
    // public function save() {
    //     $article = new Article();
    //     $article->setNom('Article 3');
    //     $article->setPrix(00);
    //     $this->entityManager->persist($article);
    //     $this->entityManager->flush();
    //     return new Response('Article enregisté avec id '.$article->getId());
    // }
    



 
}

?>