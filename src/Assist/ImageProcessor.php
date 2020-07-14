<?php

namespace App\Assist;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageProcessor
{
    /**
     * Save a file on its uploading
     * @param $item
     * @param string $storage
     * @param $request
     */
    public static function uploadImage($item, $storage, $request)
    {
        $itemName = self::getItemName($item);
        if(!$file = $request->files->get($itemName)['image'])
            $file = $request->files->get($itemName.'_update')['image'];
        if($file) {
            $fileName = $file->getClientOriginalName();
            $array = explode('.', $fileName);
            $extension = trim(array_pop($array));
            $imageDirectory = $storage . date('dmyHis');
            $newName = date('dmyHis') . '.' . $extension;
            if($path = $item->getImage()) {
                $a = explode('/', $path);
                if(count($a) > 4) {
                    self::delDir(self::getImageDir($path));
                } else {
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . $path))
                        unlink($_SERVER['DOCUMENT_ROOT'] . $path);
                }
            }
            mkdir($_SERVER['DOCUMENT_ROOT'] . $imageDirectory);
            $file->move($_SERVER['DOCUMENT_ROOT'] . $imageDirectory, $newName);
            $item->setImage($imageDirectory . '/' . $newName);
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

    public static function imageDelete($item)
    {
        if($path = $item->getImage()) {
            $a = explode('/', $path);
            if(count($a) > 4) {
                self::delDir(self::getImageDir($path));
            } else {
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . $path))
                    unlink($_SERVER['DOCUMENT_ROOT'] . $path);
            }
        }
        $item->setImage('');
    }

    /**
     * Get name in singular
     * @param $item - Entity Object
     * @return string
     */
    private static function getItemName($item)
    {
        // Fully qualified class name in lower case
        $n = strtolower(get_class($item));
        // Get rid of namespaces
        $n = (substr($n, strrpos($n, '\\') + 1));
        // Replace according word ending '-ies' / '-s'
        if($n[strlen($n) - 1] == 's') {
            if($n[strlen($n) - 2] == 'e')
                return substr($n, 0,strlen($n) - 3) . 'y';
            return substr($n, 0,strlen($n) - 1);
        }
        return $n;
    }
}
























