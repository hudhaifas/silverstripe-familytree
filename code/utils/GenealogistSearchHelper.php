<?php

/*
 * MIT License
 *
 * Copyright (c) 2016 Hudhaifa Shatnawi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 28, 2017 - 9:54:02 PM
 */
class GenealogistSearchHelper {

    private static $sort = 'CHAR_LENGTH(IndexedName) ASC';
    private static $all_names = array(
        // Similar names
        'سالم', 'سليم', 'سليمان', 'سلمان', 'مسلم',
        'عايد', 'عواد', 'عيد', 'عويد', 'عودة',
        'موسى', 'عيسى',
        'سعيد', 'سعد', 'مسعد', 'سعدى', 'سعدي', 'سعاد',
        'محسن', 'حسان',
        'فالح', 'مفلح', 'فليح', 'فلاح',
        'صالح', 'مصلح', 'صلاح',
        'ناجي', 'راجي',
        'حمود', 'حمد', 'حمدان', 'حميدان', 'حميد',
        'رشيد', 'رشدة', 'ارشيد', 'الرشدان', 'رشدي', 'رشوان', 'فواز',
        // Weird names
        'شهرزاد', 'فريال',
        // Males names
        // Femous names
        'محمد', 'محمود', 'حامد', 'حسن', 'حسين', 'مصطفى',
        // starts with ء
        'أحمد', 'أمجد', 'أسعد', 'إبراهيم', 'إسماعيل', 'أدهم', 'أوس', 'أمير', 'إدريس', 'إقبال', 'آدم', 'أشرف', 'أنس',
        // starts with ا
        'إرحيل', 'رحيل', 'إرحيلة', 'رحيلة',
        // ends with ء
        'علاء', 'بهاء',
        // ends with الدين
        'علاء الدين', 'بهاء الدين', 'زهاء الدين', 'عماد الدين', 'بدر الدين',
        'عماد', 'عمار', 'بدر', 'بدور', 'بدرية',
        // contains ء
        'فؤاد', 'سائد', 'رائد', 'عايد', 'نايل', 'نائل', 'هايل', 'صايل',
        // with ة
        'حذيفة', 'قتيبة', 'عبيدة', 'عبادة', 'سلامة', 'حمزة',
        // with عبد
        'عبدالله', 'عبدالرحمن', 'عبدالرحيم', 'عبدالمجيد', 'عبدالحميد', 'عبدالمحسن', 'عبدالحي', 'عبدالجواد', 'عبدالرؤوف', 'عبدالغفور', 'عبدالمنعم', 'عبدالوالي', 'عبدالهادي', 'عبدربه', 'عبدالإله', 'العبد', 'عبد', 'عبده',
        // with ى
        'علي', 'فادي', 'شادي', 'هادي', 'رامي', 'فندي', 'ناجي', 'راجي',
        // Females names
        // starts with ء
        'آمنة', 'إيمان', 'إخلاص', 'أروى', 'أسماء', 'آية', 'آيات',
        // ends with ء
        'غيداء', 'شفاء', 'صفاء', 'فداء', 'رجاء', 'آلاء', 'نداء', 'هنا', 'هناء', 'شيماء', 'علياء',
        // ends with ا
        'شما', 'عليا', 'فضا', 'سميا', 'صبحا', 'فلحا',
        // ends with ى
        'سلمى', 'سلوى',
        // with ة
        'سكينة', 'نجاة', 'ناجية', 'خولة', 'بسمة', 'عشبة', 'شيخة', 'نصرة', 'شريفة', 'فاطمة', 'سالمة', 'حاجة', 'حياة', 'سميرة', 'بديوية', 'زانة', 'رية', 'جهينة', 'سمية',
        'فلحة', 'ربيعة', 'طردة', 'رحيلة', 'زغندة', 'كرمة', 'بدرة', 'طولة', 'طلة', 'قطنة', 'حسنة', 'فندية', 'شمسية', 'عائشة', 'عيشة',
            //
//        '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
    );

//    private static $sort = 'IndexedName ASC';

    public static function search_objects($list, $keywords, $parentID = null) {
        $parenKey = '|' . $parentID . '|';

        $r1 = self::search_round($list, $keywords, $parenKey);

        $words = explode(" ", $keywords);
        $newQuery = '';
        foreach ($words as $input) {
            $newQuery .= self::correct($input) . ' ';
        }

//        $keywords = self::words_ends_with($keywords, array("ة", "ه", "ى", "ئ", "ء"));
//        $keywords = self::words_starts_with($keywords, array("أ", "إ", "آ", "ا"));

        $r2 = self::search_round($list, $newQuery, $parenKey);

        $results = self::merge($r1, $r2);

        $results->removeDuplicates();
        return $results->filterByCallback(function($record) {
                    return $record->canView();
                });

//        return $results;
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

    private static function search_round($list, $keywords, $parentID = null) {
        $r1 = $list->filter(self::get_filters_array('StartsWith', $keywords, $parentID))->sort(self::$sort);
        $r2 = $list->filter(self::get_filters_array('PartialMatch', $keywords, $parentID))->sort(self::$sort);

        return self::merge($r1, $r2);
    }

    private static function get_filters_array($filter, $keywords, $parentID = null) {
        $filters = array();

        $filters['IndexedName:' . $filter] = $keywords;
        if ($parentID && $parentID != '||') {
            $filters['IndexedAncestors:PartialMatch'] = $parentID;
        }

        return $filters;
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
     * 
     * @see http://php.net/manual/en/function.levenshtein.php
     */
    private static function correct($input) {
        // no shortest distance found, yet
        $shortest = -1;

        // loop through words to find the closest
        foreach (self::$all_names as $word) {

            // calculate the distance between the input word,
            // and the current word
            $lev = levenshtein($input, $word);

            // check for an exact match
            if ($lev == 0) {

                // closest word is this one (exact match)
                $closest = $word;
                $shortest = 0;

                // break out of the loop; we've found an exact match
                break;
            }

            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($lev <= $shortest || $shortest < 0) {
                // set the closest match, and shortest distance
                $closest = $word;
                $shortest = $lev;
            }
        }

        return $closest;
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

    public static function get_all_descendants($parent) {
        if ($parent instanceof Person) {
            $parent = $parent->ID;
        }

        $key = '|' . $parent . '|';

        $descendants = DataObject::get('Person')->filter(array(
            'IndexedAncestors:PartialMatchFilter' => $key
        ));

        return $descendants;
    }

    public static function explode_keywords($keywords) {
        $pieces = 0;
//        preg_match_all("/\\(([^)]*)\\)/", $keywords, $pieces);
        preg_match_all('/"([^"]+)"/', $keywords, $pieces);
//        print_r($pieces);
//        die();
        $clanName = $pieces[1];

        $nameSeries = str_replace($pieces[0], "", $keywords);

        $clan = DataObject::get('Clan')->filter(array('Name' => $clanName))->first();
        $clanID = null;

        if ($clan) {
//            var_dump($clanName);
//            var_dump($clan->Name);
            $clanID = $clan->ID;
        }

//        var_dump($nameSeries);

        return array(
            'ClanID' => $clanID,
            'NameSeries' => $nameSeries,
        );
    }

}
