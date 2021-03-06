<?php

namespace Model;

use PDO;
use PDOException;

class DBManager
{
    private $dbh;
    
    private static $instance = null;
    public static function getInstance()
    {
        if (self::$instance === null)
            self::$instance = new DBManager();
        return self::$instance;
    }
    
    private function __construct()
    {
        $this->dbh = null;
    }
    
    private function connectToDb()
    {
        global $config;
        $db_config = $config['db_config'];
        $dsn = 'mysql:dbname='.$db_config['name'].';charset=utf8;host='.$db_config['host'];
        $user = $db_config['user'];
        $password = $db_config['pass'];
        
        try {
            $dbh = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
        
        return $dbh;
    }
    
    protected function getDbh()
    {
        if ($this->dbh === null)
            $this->dbh = $this->connectToDb();
        return $this->dbh;
    }
    
    public function insert($table, $data = [])
    {
        $dbh = $this->getDbh();
        $query = 'INSERT INTO `' . $table . '` VALUES (null,';
        $first = true;
        foreach ($data AS $k => $value)
        {
            if (!$first)
                $query .= ', ';
            else
                $first = false;
            $query .= ':'.$k;
        }
        $query .= ')';
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        return true;
    }
    
    function findOne($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $data->fetch();
        return $result;
    }
    
    function findOneSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    
    function findAll($query)
    {
        $dbh = $this->getDbh();
        $data = $dbh->query($query, PDO::FETCH_ASSOC);
        $result = $data->fetchAll();
        return $result;
    }
    
    function findAllSecure($query, $data = [])
    {
        $dbh = $this->getDbh();
        $sth = $dbh->prepare($query);
        $sth->execute($data);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function  aws_signed_request($region,$params,$public_key,$private_key,$associate_tag)
    {

        if ($region == 'jp') {
            $host = "ecs.amazonaws." . $region;
        } else {
            $host = "webservices.amazon." . $region;
        }

        $method = "GET";
        $uri = "/onca/xml";


        $params["Service"] = "AWSECommerceService";
        $params["AWSAccessKeyId"] = $public_key;
        $params["AssociateTag"] = $associate_tag;
        $params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
        $params["Version"] = "2017-04-28";

        /* The params need to be sorted by the key, as Amazon does this at
          their end and then generates the hash of the same. If the params
          are not in order then the generated hash will be different thus
          failing the authetication process.
        */
        ksort($params);

        $canonicalized_query = array();

        foreach ($params as $param => $value) {
            $param = str_replace("%7E", "~", rawurlencode($param));
            $value = str_replace("%7E", "~", rawurlencode($value));
            $canonicalized_query[] = $param . "=" . $value;
        }

        $canonicalized_query = implode("&", $canonicalized_query);

        $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;

        /* calculate the signature using HMAC with SHA256 and base64-encoding.
           The 'hash_hmac' function is only available from PHP 5 >= 5.1.2.
        */
        $signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));

        /* encode the signature for the request */
        $signature = str_replace("%7E", "~", rawurlencode($signature));

        /* create request */
        $request = "http://" . $host . $uri . "?" . $canonicalized_query . "&Signature=" . $signature;

        /* I prefer using CURL */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $xml_response = curl_exec($ch);

        /* If cURL doesn't work for you, then use the 'file_get_contents'
           function as given below.
        */

        if ($xml_response === False) {
            return False;
        } else {
            /* parse XML */
            $parsed_xml = @simplexml_load_string($xml_response);
            return ($parsed_xml === False) ? False : $parsed_xml;
        }
    }
}