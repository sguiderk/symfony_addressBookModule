<?php 
namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $directory;
    
    public function __construct($directory)
    {
        $this->directory = $directory;
    }
    
    public function uploadfile(UploadedFile $file)
    {
        $filename = md5(uniqid()).'.'.$file->guessExtension();
        try {
            $file->move($this->directory,$filename);
            return $filename;
        }catch(\Exception $e)
        {
            //add exception into the log file 
            return false;
        }
    }
}


?> 