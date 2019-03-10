<?php
/**
 * Created by PhpStorm.
 * User: Esteban
 * Date: 09/03/2019
 * Time: 22:49
 */

namespace ErrorHandler;
class EHError
{
    /* TODO: return object
         * ->DATE
         * ->HOST
         * ->TYPE
         * ->FILE
         * ->MSG
         * ->MOREDETAILS
         * ->MOREPARAMS
    */
    private $date = null;
    private $host = "";
    private $code = "";
    private $type = "";
    private $file = "";
    private $msg = "";
    private $moreDetails = null;
    private $additionalParameters = null;

    public function __construct($data = [])
    {
        if (empty($data)) {
            throw new \Exception("Ne peut pas instancier " . __CLASS__ . " avec \"\$data\" vide.");
        }

        $this->date = $data["DATE"] ?? null;
        $this->host = $data["HOST"] ?? "";
        $this->type = $data["TYPE"] ?? "";
        $this->code = $data["CODE"] ?? "";
        $this->file = $data["FILE"] ?? "";
        $this->msg = $data["MSG"] ?? "Erreur inconnue.";
        $this->moreDetails = $data["MOREDETAILS"] ?? [];
        $this->additionalParameters = $data["ADDITIONALPARAMETERS"] ?? [];
    }

    /**
     * @return mixed|null
     */
    public function getDate(): ?mixed
    {
        return $this->date;
    }

    /**
     * @return mixed|string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed|string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed|string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed|string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @return array|mixed|null
     */
    public function getMoreDetails()
    {
        return $this->moreDetails;
    }

    /**
     * @return array|mixed|null
     */
    public function getMoreParams()
    {
        return $this->moreParams;
    }

    /**
     * @return mixed|string
     */
    public function getCode()
    {
        return $this->code;
    }


}