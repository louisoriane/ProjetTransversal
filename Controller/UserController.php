<?php

namespace Controller;

use Model\UserManager;
use Model\AmazonProductAPI;

class UserController extends BaseController
{
    public function loginAction(){
        if(empty($_SESSION['user_id'])){
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $manager = UserManager::getInstance();
                if ($manager->userCheckLogin($_POST))
                {
                    $manager->userLogin($_POST['email']);
                    $this->redirect('home');
                }
                else {
                    $error = "Invalid username or password";
                }
            }
            echo $this->renderView('register.html.twig', ['error' => $error]);
        }
        else{
            $this->redirect('home');
        }
    }

    public function logoutAction()
    {
        session_destroy();
        $this->redirect('home');
    }

    public function registerAction()
    {
        if(empty($_SESSION['user_id'])){
            $error = '';
            if ($_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $manager = UserManager::getInstance();
                if ($manager->userCheckRegister($_POST))
                {
                    $manager->userRegister($_POST);
                    $error = "Inscription réussi!";
                }
                else {
                    $error = "Invalid data";
                }
            }
            echo $this->renderView('register.html.twig', ['error' => $error]);
        }
        else{
            $this->redirect('home');
        }
    }

    public function deleteAction()
    {
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $manager->deleteUser($_SESSION['user_id']);
            $this->logoutAction();
        }
        else{
            $this->redirect('home');
        }
    }

    public function mailAction(){
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(null == $_POST['question'] || null == $_POST['email'] || null == $_POST['nom'] || null == $_POST['prenom']){
                $error = "empty form";
            }
            else{
                $manager = UserManager::getInstance();
                if($manager->sendMail($_POST)){
                    $error = "Mail Sent";
                }
                else{
                    $error = "Failed to sent mail";
                }
                echo $this->renderView('home.html.twig', ['error' => $error]);
            }
        }
        else{
            $this->redirect('login');
        }
    }

    public function productAction()
    {
        $error = '';
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            var_dump($_POST);
            echo $this->renderView('product.html.twig',
                ['user' => $user,
                'product' =>$_POST]);
        }
        else{
            $error = "Vous devez être connecté pour effectuer cette action";
            echo $this->renderView('home.html.twig',
                ['error'=>$error]);
        }
    }

    public function causeAction()
    {
        $error = '';
        if (!empty($_SESSION['user_id']))
        {
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            echo $this->renderView('cause.html.twig',
                ['user' => $user,
                    'product' =>$_POST]);
        }
        else{
            $error = "Action illegal";
            echo $this->renderView('home.html.twig',
                ['error'=>$error]);
        }
    }

    public function buyAction(){
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(null == $_POST['itemname'] || null == $_POST['seller'] || null == $_POST['price']){
                $error = "Item error";
                $manager = UserManager::getInstance();
                $user = $manager->getUserById($_SESSION['user_id']);
                echo $this->renderView('home.html.twig',
                    ['error' => $error,
                    'user' =>$user]);
            }
            else{
                $manager = UserManager::getInstance();
                $user = $manager->getUserById($_SESSION['user_id']);
                if($manager->uploadItem($_POST)){
                    echo $this->renderView('thank.html.twig',
                        ['error' => $error,
                        'user' =>$user]);
                }
                else{
                    $error = "Failed to buy";
                    echo $this->renderView('home.html.twig',
                        ['error' => $error,
                         'user' => $user]);
                }
            }
        }
        else{
            $this->redirect('home');
        }
    }

    public function histoAction(){
        $error = '';
        if(!empty($_SESSION['user_id'])){
            $manager = UserManager::getInstance();
            $user = $manager->getUserById($_SESSION['user_id']);
            $histo = $manager->userHisto($user['email']);
            $spent = $manager->moneySpent($user['email']);
            echo $this->renderView('profile.html.twig',
                ['histos' => $histo,
                 'user' => $user,
                 'spent' => $spent]);

        }
        else{
            $this->redirect('home');
        }
    }

    public function searchAction(){
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $amazon = AmazonProductAPI::getInstance("AKIAJKEJQMISIWD2QPKQ", "qwQ4v1yLZQfPJ1dbyIFnDXCRgWOeUh+XJKTp67Mk", "fr", "cashallo");
            $results = null;
            try {
                $results = $amazon->searchProducts($_POST['product'],
                    AmazonProductAPI::ALL,
                    "TITLE");

            }
            catch(Exception $e)
            {
                echo $e->getMessage();
            }
            $arr = [];
            if ($results) {
                for($i = 0 ; $i < $results->Items->Item->count() ; $i++){
                    if('' !== (string)$results->Items->Item[$i]->ItemAttributes->ListPrice->Amount){
                        $arrne['title'] = (string)$results->Items->Item[$i]->ItemAttributes->Title;
                        $arrne['price'] = (string)$results->Items->Item[$i]->ItemAttributes->ListPrice->Amount;
                        $arrne['brand'] = (string)$results->Items->Item[$i]->ItemAttributes->Brand;
                        $arrne['img'] = (string)$results->Items->Item[$i]->MediumImage->URL;
                        //$arrne['Content'] = (string)$results->Items->Item[$i]->EditorialReviews->EditorialReview->Content;
                        
                        array_push( $arr, $arrne );
                    }
                }
            }
            $data = json_encode($arr);
            echo($data);
        }
        else{
            $this->redirect('home');
        }
    }
}
