<?php

namespace Controller;

use Model\UserManager;

class DefaultController extends BaseController
{
    public function homeAction(){
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            echo $this->renderView('home.html.twig',
                ['user' => $user]);
        }
        else{
            echo $this->renderView('home.html.twig');
        }
    }


    public function articleAction()
    {
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            $article = $manager->getArticle($_POST['id']);
            echo $this->renderView('article.html.twig',
                ['user' => $user,
                'article' => $article]);
        }
        else{
            $manager = UserManager::getInstance();
            $article = $manager->getArticle($_POST['id']);
            echo $this->renderView('article.html.twig',
                ['article' => $article]);
        }
    }

    public function profilAction()
    {
        $error = "";
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            $spent = $manager->moneySpent($user['email']);
            echo $this->renderView('profile.html.twig',
                ['user' => $user,
                'spent' => $spent]);
        }
        else{
            $error = "Vous devez être connecté pour acceder à votre profil";
            echo $this->renderView('register.html.twig',
                ['error' => $error]);
        }
    }

     public function actualitiesAction()
    {
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            $articles = $manager->getAllArticle();
            echo $this->renderView('actualities.html.twig',
                ['user' => $user,
                 'articles' =>$articles]);
        }
        else{
            $manager = UserManager::getInstance();
            $articles = $manager->getAllArticle();
            echo $this->renderView('actualities.html.twig',
                ['articles' =>$articles]);
        }
    }

     public function contactAction()
    {
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            echo $this->renderView('contact.html.twig',
                ['user' => $user]);
        }
        else{
            echo $this->renderView('contact.html.twig');
        }
    }


    public function partnerAction()
    {
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            echo $this->renderView('partner.html.twig',
                ['user' => $user]);
        }
        else{
            echo $this->renderView('partner.html.twig');
        }
    }
}
