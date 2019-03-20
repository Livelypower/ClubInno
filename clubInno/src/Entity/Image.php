<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/20/2019
 * Time: 11:19 AM
 */


namespace App\Entity;

use Symfony\Component\HttpFoundation\File\File;

class Image
{
    protected $file;

    public function setFile(File $myFile = null)
    {
        $this->file = $myFile;
    }

    public function getFile()
    {
        return $this->file;
    }
}