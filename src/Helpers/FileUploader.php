<?php

namespace App\Helpers;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(EntityManagerInterface $entityManager)
    {
    }
    public function upload(UploadedFile $file, string $directory, string $name = '') : string
    {

        $fileName = ($name ? $name. '-' : $name) . uniqid() . '.' . $file->guessExtension();
        $file->move($directory, $fileName);

        return $fileName;
    }

    public function delete(string $fileName, string $directory): bool
    {
        return unlink($directory . DIRECTORY_SEPARATOR . $fileName);
    }

}