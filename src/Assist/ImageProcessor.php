<?php


namespace App\Assist;


use Doctrine\ORM\Mapping\Entity;

class ImageProcessor
{
    /**
     * Save a file on its uploading
     * @param Entity $item
     * @param string $storage
     */
    public static function uploadImage($item, $storage)
    {
        if (is_uploaded_file($_FILES['item[image]']['tmp_name'])) {
            $fileName = $_FILES['image']['name'];
            $array = explode('.', $fileName);
            $extension = trim(array_pop($array));
            $imageDirectory = $storage . date('dmyHis');
            $imagePath = $imageDirectory . '/' . date('dmyHis') . '.' . $extension;

            if($path = $item->getImage())
                self::delDir(self::getImageDir($path));

            mkdir($_SERVER['DOCUMENT_ROOT'] . $imageDirectory);
            move_uploaded_file($_FILES['image']['tmp_name'],
                $_SERVER['DOCUMENT_ROOT'] . $imagePath
            );
            $item->setImage($imagePath);
        }
    }

    /**
     * Delete image file with its directory
     * @param $path string path
     */
    public static function delImageFolder($path)
    {
        self::delDir(self::getImageDir($path));
    }

    /**
     * Delete directory if it exists
     * @param $directory string path
     */
    public static function delDir($directory)
    {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $directory))
            self::delTree($_SERVER['DOCUMENT_ROOT'] . $directory);
    }

    /**
     * Delete folder with underlying files
     * @param $dir string path
     * @return bool - true on deletion
     */
    public static function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file)
            if(is_dir($dir.'/'.$file)) self::delTree($dir.'/'.$file);
            else unlink($dir.'/'.$file);
        return rmdir($dir);
    }

    /**
     * Get the folder of the file by path
     * @param $path
     * @return string
     */
    public static function getImageDir($path)
    {
        $a = explode('/', $path);
        unset($a[count($a) - 1]);
        return implode('/', $a);
    }
}