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
    private $formFactory;


    public function __construct(EntityManagerInterface $entityManager,FormFactoryInterface $formFactory) {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
    }

        
    #[Route('/', name: 'article_list')]
    public function home()
    {
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        $articles= $this->entityManager->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig',['articles'=> $articles]);
    }

    #[Route('/articles/create', name: 'new_article',methods: ["GET"])]
    public function new(Request $request)
    {
    $article = new Article();
    $form =  $this->formFactory->createFormBuilder($article)->add('nom', TextType::class)->add('prix', TextType::class)->add('save', SubmitType::class, array('label' => 'Créer'))->getForm();
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        $this->entityManager->getDoctrine()->getManager();
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return $this->redirectToRoute('article_list');
    }
        return $this->render('articles/new.html.twig',['form' => $form->createView()]);
    }


    #[Route('/article/save', name: 'save')]
    public function save() {
        $article = new Article();
        $article->setNom('Article 3');
        $article->setPrix(00);
        $this->entityManager->persist($article);
        $this->entityManager->flush();
        return new Response('Article enregisté avec id '.$article->getId());
    }
    



 
}

?>