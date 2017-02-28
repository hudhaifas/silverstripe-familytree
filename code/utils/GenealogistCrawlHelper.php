<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Feb 28, 2017 - 7:57:01 AM
 */
class GenealogistCrawlHelper {

    public static $GENERATION_FEMALE_GAP = 18;
    public static $GENERATION_MALE_GAP = 26;
    public static $GENERATION_MAX_AGE = 80;

    /// Calucluates the estemiated BIRTH year ///
    public static function calculate_min_person_year(Person $person, $space = '') {
        $eldestChild = self::calculate_eldest_child_year($person, $space . '-');
        $youngestParent = self::calculate_youngest_parent_year($person, $space . '-');

        $estimated = 0;

        if (self::is_year($eldestChild) && self::is_year($youngestParent)) {
            $estimated = ($eldestChild + $youngestParent) / 2;
        } else if (self::is_year($eldestChild)) {
            $estimated = $eldestChild;
        } else if (self::is_year($youngestParent)) {
            $estimated = $youngestParent;
        }

        if (self::is_year($estimated) && $estimated < self::this_year()) {
//            echoln($space . 'Estimated for ' . $person->getFullName() . ': ' . $estimated . '<br />');
            return $estimated;
        }

        return null;
    }

    private static function calculate_eldest_child_year(Person $person, $space = '') {
        $eldestChild = self::get_eldest_child_year($person, $space . '-');

        if (self::is_year($eldestChild)) {
            $eldestChild -= $person->isFemale() ? self::$GENERATION_FEMALE_GAP : self::$GENERATION_MALE_GAP;
        }

        if ($eldestChild) {
//            echoln($space . 'Calculated from children for ' . $person->getFullName() . ': ' . $eldestChild . '<br />');
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

        if ($youngestParent) {
//            echoln($space . 'Calculated from parents for ' . $person->getFullName() . ': ' . $youngestParent . '<br />');
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
        return $minYear + self::$GENERATION_MAX_AGE;
    }

    private static function is_year($date) {
        return $date && is_numeric($date) && $date > 0 && $date < PHP_INT_MAX;
    }

    private static function this_year() {
        return date('Y');
    }

}
