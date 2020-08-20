<?php

namespace Bnw\SmsManager;

class SmsMessage
{
    /**
    * The text content of the message.
    *
    * @var string
    */
    private $message;

    /**
    * Value to know if will write the message in the DB
    *
    * @var string
    */
    private $recordDb = true;

    /**
    * Value to know if will keep the accents of the message
    *
    * @var string
    */
    private $accents = false;

    /**
    * @param string $message
    * 
    * @return SmsMessage
    */
    public function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
    * @param bool $recordDb
    *
    * @return SmsMessage
    */
    public function recordDb(bool $recordDb)
    {
        $this->recordDb = $recordDb;

        return $this;
    }

    /**
    * @param bool $accents
    * 
    * @return SmsMessage
    */
    public function accents(bool $accents)
    {
        $this->accents = $accents;

        return $this;
    }

    /**
    * @return string $message
    */
    public function getMessage()
    {
        return $this->accents ? $this->message : $this->messageWithoutAccents();
    }

    /**
    * @return string $recordDb
    */
    public function getRecordDb()
    {
        return $this->recordDb;
    }

    /**
    * @return string $accents
    */
    public function getAccents()
    {
        return $this->accents;
    }

    /**
    * @return string $message without accents
    */
    private function messageWithoutAccents()
    {
        $unwanted_array = [
            'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y'
        ];

        return strtr( $this->message, $unwanted_array );
    }
}
