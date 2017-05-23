<?php

namespace Model;

/**
 * Class to access Amazons Product Advertising API
 * @author Sameer Borate
 * @link http://www.codediesel.com
 * @version 1.0
 * All requests are not implemented here. You can easily
 * implement the others from the ones given below.
 */


/*
Permission is hereby granted, free of charge, to any person obtaining a
copy of this software and associated documentation files (the "Software"),
to deal in the Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish, distribute, sublicense,
and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
DEALINGS IN THE SOFTWARE.
*/


class AmazonProductAPI
{
    private static $instance = null;
    public static function getInstance($public, $private, $local_site, $associate_tag)
    {
        if (self::$instance === null)
            self::$instance = new AmazonProductAPI($public, $private, $local_site, $associate_tag);
        return self::$instance;
    }


    public function __construct($public, $private, $local_site, $associate_tag){
        $this->DBManager = DBManager::getInstance();
        $this->public_key = $public;
        $this->private_key = $private;
        $this->local_site = $local_site;
        $this->associate_tag = $associate_tag;
        require_once('model/DBManager.php');
    }

    /*
        Only three categories are listed here.
        More categories can be found here:
        http://docs.amazonwebservices.com/AWSECommerceService/latest/DG/APPNDX_SearchIndexValues.html
    */

    const MUSIC = "Music";
    const DVD   = "DVD";
    const GAMES = "VideoGames";
    CONST ALL = "All";


    /**
     * Check if the xml received from Amazon is valid
     *
     * @param mixed $response xml response to check
     * @return bool false if the xml is invalid
     * @return mixed the xml response if it is valid
     * @return exception if we could not connect to Amazon
     */


    private function verifyXmlResponse($response)
    {
        if ($response === False)
        {
            throw new Exception("Could not connect to Amazon");
        }
        else
        {
            if (isset($response->Items->Item->ItemAttributes->Title))
            {
                return ($response);
            }
            else
            {
                throw new Exception("Invalid xml response.");
            }
        }
    }


    /**
     * Query Amazon with the issued parameters
     *
     * @param array $parameters parameters to query around
     * @return simpleXmlObject xml query response
     */

    public function queryAmazon($parameters)
    {
        return $this->DBManager->aws_signed_request($this->local_site, $parameters, $this->public_key, $this->private_key, $this->associate_tag);
    }


    /**
     * Return details of products searched by various types
     *
     * @param string $search search term
     * @param string $category search category
     * @param string $searchType type of search
     * @return mixed simpleXML object
     */

    public function searchProducts($search, $category, $searchType = "UPC")
    {
        $parameters = array("Operation"     => "ItemSearch",
            "Keywords"      => $search,
            "SearchIndex"   => $category,
            "ResponseGroup" => "Medium");


        $xml_response = $this->queryAmazon($parameters);

        return $this->verifyXmlResponse($xml_response);

    }

}

?>
