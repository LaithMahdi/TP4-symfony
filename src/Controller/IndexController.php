<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    // #[Route('/{name}', name: 'Index',methods:['GET','HEAD'])]
    // public function index($name):Response
    // {
    
    //    return $this->render("index.html.twig",['name'=>$name]);
    // }
    #[Route('/', name: 'home')]

    public function home()
    {
        $articles = ['Artcile1', 'Article 2','Article 3'];
        return $this->render('articles/index.html.twig',['articles' => $articles]);
    }
}

?>