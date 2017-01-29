<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 28, 2017 - 9:54:02 PM
 */
class GenealogistSearchHelper {

    private static $sort = 'CHAR_LENGTH(IndexedName) ASC';

//    private static $sort = 'IndexedName ASC';

    public static function search_objects($list, $keywords) {
        $r1 = self::search_round($list, $keywords);

        $keywords = self::words_ends_with($keywords, array("ة", "ه", "ى", "ئ", "ء"));
        $keywords = self::words_starts_with($keywords, array("أ", "إ", "آ", "ا"));

        $r2 = self::search_round($list, $keywords);

        $results = self::merge($r1, $r2);

        $results->removeDuplicates();
        return $results;
    }

    private static function merge($list1, $list2) {
        $results = new ArrayList;

        foreach ($list1 as $obj) {
            $results->push($obj);
        }

        foreach ($list2 as $obj) {
            $results->push($obj);
        }
        return $results;
    }

    private static function search_round($list, $keywords) {
        $r1 = $list->filterAny(array(
                    'IndexedName:StartsWith' => $keywords,
                ))->sort(self::$sort);

        $r2 = $list->filterAny(array(
                    'IndexedName:PartialMatch' => $keywords,
                ))->sort(self::$sort);

        return self::merge($r1, $r2);
    }

    private static function words_starts_with($keywords, $vowels) {
        $words = explode(" ", $keywords);
        $newwords = '';

        foreach ($words as $word) {
            foreach ($vowels as $vowel) {
                if (self::starts_with($word, $vowel)) {
                    $word = substr_replace(trim($word), "_", 0, 2);
                }
            }

            $newwords .= $word . ' ';
        }

        return trim($newwords);
    }

    private static function words_ends_with($keywords, $vowels) {
        $words = explode(" ", $keywords);
        $newwords = '';

        foreach ($words as $word) {
            foreach ($vowels as $vowel) {
                if (self::ends_with($word, $vowel)) {
//                    $word = str_replace($vowel, "_", $word);
                    $word = substr(trim($word), 0, -2) . "_";
                }
            }

            $newwords .= $word . ' ';
        }

        return trim($newwords);
    }

    /**
     * http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function starts_with($word, $needle) {
        $length = strlen($needle);
        return (substr($word, 0, $length) === $needle);
    }

    /**
     * http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function ends_with($word, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($word, -$length) === $needle);
    }

}
