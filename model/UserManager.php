<?php

namespace Model;

class UserManager
{
    private $DBManager;

    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new UserManager();
        return self::$instance;
    }

    private function __construct()
    {
        $this->DBManager = DBManager::getInstance();
    }

    public function getUserById($id)
    {
        $id = (int)$id;
        $data = $this->DBManager->findOne("SELECT * FROM users WHERE id = ".$id);
        return $data;
    }

    public function getUserByUsername($email)
    {
        $data = $this->DBManager->findOneSecure("SELECT * FROM users WHERE email = :email",
                                ['email' => $email]);
        return $data;
    }

    public function userCheckRegister($data)
    {
        if (empty($data['prenom']) OR empty($data['nom']) OR empty($data['email']) OR empty($data['password']))
            return false;
        $data = $this->getUserByUsername($data['email']);
        if ($data !== false)
            return false;
        // TODO : Check valid email
        return true;
    }


    private function userHash($pass)
    {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        return $hash;
    }

    public function userRegister($data)
    {
        $user['prenom'] = $data['prenom'];
        $user['nom'] = $data['nom'];
        $user['password'] = $this->userHash($data['password']);
        $user['email'] = $data['email'];
        $user['cagnotte'] = 0;
        $user['admin'] = 0;
        $this->DBManager->insert('users', $user);
    }

    public function userCheckLogin($data)
    {
        if (empty($data['email']) OR empty($data['password']))
            return false;
        $user = $this->getUserByUsername($data['email']);
        if ($user === false)
            return false;
        if (!password_verify($data['password'], $user['password']))
            return false;
        return true;
    }

    public function userLogin($username)
    {
        $data = $this->getUserByUsername($username);
        if ($data === false)
            return false;
        $_SESSION['user_id'] = $data['id'];
        return true;
    }

    public function userHisto($username) {
        $data = $this->DBManager->findAllSecure("SELECT * FROM achat WHERE username = :username",
            ['username' => $username]);
        return $data;
    }

    public function moneySpent($username){
        $data = $this->DBManager->findAllSecure("SELECT SUM(price) as spent FROM achat WHERE username = :username",
            ['username' => $username]);
        return $data;
    }

    public function uploadItem($data){
        $email = $this->getUserById($_SESSION['user_id']);
        $user['username'] = $email['email'];
        $user['itemname'] = $data['itemname'];
        echo strlen($data['seller']);
        if (strlen($data['seller']) == 0) {
           $user['seller'] = 'none';
        }
        else {
            $user['seller'] = $data['seller'];
        }
        $user['price'] = intval($data['price']);
        $user['cause'] = $data['cause'];
        $user['date_creation'] = date('m/d/Y h:i:s a', time());
        if($this->DBManager->insert('achat', $user)){
            return true;
        }
        else{
            return false;
        }
    }

    public function sendMail($data){
        date_default_timezone_set('Etc/UTC');
        require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
        $mail = new \PHPMailer();
        $mail->IsSMTP(); // enable SMTP
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
        $mail->SMTPAuth = true; // authentication enabled
        $mail->SMTPSecure = 'tls'; // secure transfer enabled REQUIRED for Gmail
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587; // or 587
        $mail->IsHTML(true);
        $mail->Username = "cashallo.help@gmail.com";
        $mail->Password = "cashallohelp";
        $mail->SetFrom("cashallo.help@gmail.com",$data['prenom']." ".$data['nom']);
        $mail->Subject = $data['email'];
        $mail->Body = $data['question'];
        $mail->AddAddress("cashallo.help@gmail.com");
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            return true;
        }

    }

    public function deleteUser($id){
        $data = $this->DBManager->findOneSecure("DELETE FROM users WHERE id =" . $id);
        if ($data === false)
            return false;
        return true;
    }

    public function getArticle($id) {
        $data = $this->DBManager->findOneSecure("SELECT * FROM article WHERE id = :id",
            ['id' => $id]);
        return $data;
    }

    public function getAllArticle() {
        $data = $this->DBManager->findAllSecure("SELECT * FROM article");
        return $data;
    }
}
