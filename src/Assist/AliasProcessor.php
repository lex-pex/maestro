<?php

namespace App\Assist;

use Doctrine\Persistence\ObjectRepository;

class AliasProcessor
{
    /**
     * Creates the Alias from the Name or Title
     * in the suitable way to be the Url-Route
     * @param string $str
     * @param ObjectRepository $repository
     * @return string
     * @internal param object $entity
     */
    public static function getAlias(string $str, ObjectRepository $repository)
    {
        return self::getAliasUnique(self::getTranslation($str), $repository);
    }

    /**
     * Call on Entity Update to handle the alias
     * @param string $alias
     * @param string $text
     * @param object $item
     * @param ObjectRepository $repository
     */
    public static function aliasUpdate(string $alias, string $text, object $item, ObjectRepository $repository) {
        if($alias != $item->getAlias()) { // If changed
            if (!$alias) { // If Empty
                if (($translation = self::getTranslation($text)) != $item->getAlias()) { // If it will not be the same alias
                    $item->setAlias(self::getAliasUnique($translation, $repository));
                }
            } else { // If changed and not empty
                $item->setAlias(self::getAliasUnique($alias, $repository));
            }
        }
    }

    /**
     * Get raw translated alias
     * @param string $text
     * @return string
     */
    public static function getTranslation(string $text) {
        $symbols = trim(preg_replace('/[\n\r]{2,}/', "\n", $text));
        $wordsArray = explode(' ', $symbols);
        $wordsArray = array_slice($wordsArray, 0, 5);
        $symbols = implode(' ', $wordsArray);
        $symbols = mb_strtolower($symbols);
        $str = $symbols;
        $chars = [];
        for ($i = 0; $i < mb_strlen($str); $i++)
            $chars[] = mb_substr($str, $i, 1);
        $result = '';
        for ($i = 0; $i < count($chars); $i ++)
            $result .= self::changeSymbol($chars[$i]);
        return $result;
    }

    /**
     * Handle the case if the alias is not unique
     * @param string $alias
     * @param ObjectRepository $repository
     * @param string $postfix - additional symbol for uniqueness
     * @return string
     */
    public static function getAliasUnique(string $alias, ObjectRepository $repository, string $postfix = 'I')
    {
        while ($repository->findOneBy(['alias' => $alias]))
            $alias .= $postfix;
        return $alias;
    }

    /**
     * Replace Cyrillic characters with Latin
     * The 'Snake_Case' is implying (underscore on space)
     * @param $symbol
     * @return mixed
     */
    private static function changeSymbol($symbol)
    {
        $map = [' ' => '_','а' => 'a','б' => 'b','в' => 'v','г' =>'g','д' => 'd','е' => 'e','ё' => 'yo','.'=>'','%'=>'pc','='=>'',
            'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'і' => 'i', 'ї' => 'j', 'к' => 'k','л' => 'l',
            'м' => 'm','н' => 'n','о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch','ш' => 'sh', 'щ' => 'shch', 'ъ' => '','ы' => 'y',
            'ь' => '', 'э' => 'e', 'ю' => 'yu','я' => 'ya','?' => '','!' => '', '/' => '', '\\' => '', ',' => '','"' => '', "'" => ''];
        return array_key_exists($symbol, $map) ? $map[$symbol] : $symbol;
    }
}




