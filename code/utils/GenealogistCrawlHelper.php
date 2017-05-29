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
 * @version 1.0, Feb 28, 2017 - 7:57:01 AM
 */
class GenealogistCrawlHelper {

    public static $GENERATION_FEMALE_GAP = 18;
    public static $GENERATION_MALE_GAP = 26;
    public static $GENERATION_MAX_AGE = 63;
    private static $DEBUG = false;

    /// Calucluates the estemiated BIRTH year ///
    public static function calculate_min_person_year(Person $person, $space = '') {
        if ($person->BirthDate) {
            return $person->BirthDate;
        }
            self::_debugln();

        $eldestChild = self::calculate_eldest_child_year($person, $space . '-');
        $youngestParent = self::calculate_youngest_parent_year($person, $space . '-');

        $estimatedYear = null;

        if (self::is_year($eldestChild) && self::is_year($youngestParent)) {
            $estimatedYear = ($eldestChild + $youngestParent) / 2;
        } else if (self::is_year($eldestChild)) {
            $estimatedYear = $eldestChild;
        } else if (self::is_year($youngestParent)) {
            $estimatedYear = $youngestParent;
        }

        if (self::is_year($estimatedYear)) {
            self::_debugln($space . 'Estimated for ' . $person->getFullName(false) . ': ' . $estimatedYear);
        }

        return self::is_year($estimatedYear) ? $estimatedYear : null;
    }

    private static function calculate_eldest_child_year(Person $person, $space = '') {
        $eldestChild = self::get_eldest_child_year($person, $space . '-');

        if (self::is_year($eldestChild)) {
            $eldestChild -= $person->isFemale() ? self::$GENERATION_FEMALE_GAP : self::$GENERATION_MALE_GAP;
        }

        if (self::is_year($eldestChild)) {
            self::_debugln($space . 'Calculated from children for ' . $person->getFullName(false) . ': ' . $eldestChild);
        }

        return self::is_year($eldestChild) ? $eldestChild : null;
    }

    private static function get_eldest_child_year(Person $person, $space = '') {
        $children = GenealogistHelper::get_children($person);
        if (!$children->Count()) {
            return null;
        }

        $dates = array(PHP_INT_MAX);

        foreach (GenealogistHelper::get_children($person) as $child) {
            $date = $child->getBirthYear();
            if ($date == null) {
                $date = self::calculate_eldest_child_year($child, $space);
            }

            if (self::is_year($date)) {
                $dates[] = $date;
            }
        }

        $minChild = min($dates);

        return self::is_year($minChild) ? $minChild : null;
    }

    private static function calculate_youngest_parent_year(Person $person, $space = '') {
        $youngestParent = self::get_youngest_parent_year($person, $space . '-');

        if (self::is_year($youngestParent)) {
            $youngestParent += $person->isFemale() ? self::$GENERATION_FEMALE_GAP : self::$GENERATION_MALE_GAP;
        }

        if (self::is_year($youngestParent)) {
            self::_debugln($space . 'Calculated from parents for ' . $person->getFullName(false) . ': ' . $youngestParent);
        }

        return self::is_year($youngestParent) ? $youngestParent : null;
    }

    private static function get_youngest_parent_year(Person $person, $space = '') {
        $dates = array(0);

        if ($person->Father()->exists()) {
            $father = $person->Father()->getBirthYear();
            if (self::is_year($father)) {
                $dates[] = $father;
            } else {
                $dates[] = self::calculate_youngest_parent_year($person->Father(), $space);
            }
        }

        if ($person->Mother()->exists()) {
            $mother = $person->Mother()->getBirthYear();
            if (self::is_year($mother)) {
                $dates[] = $mother;
            } else {
                $dates[] = self::calculate_youngest_parent_year($person->Mother(), $space);
            }
        }

        $parents = max($dates);

        return self::is_year($parents) ? $parents : null;
    }

    /// Calucluates the estemiated DEATH year ///
    public static function calculate_max_person_year(Person $person, $minYear) {
        return self::is_year($minYear) ? $minYear + self::$GENERATION_MAX_AGE : null;
    }

    private static function is_year($date) {
        return $date && is_numeric($date) && $date > 0 && $date < self::this_year();
    }

    private static function this_year() {
        return date('Y');
    }

    private static function _debug($msg) {
        if (self::$DEBUG) {
            echoln($msg);
        }
    }

    private static function _debugln($msg = '') {
        if (self::$DEBUG) {
            print_r($msg . '<br />');
        }
    }

}
