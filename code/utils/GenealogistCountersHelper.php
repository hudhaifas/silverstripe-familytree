<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, May 31, 2017 - 9:44:35 AM
 */
class GenealogistCountersHelper {

    /// Counts ///
    protected static $cache_counters = array();

    /**
     * Counts the of all descendants.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_descendants($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        return self::count_males($person, $state) + self::count_females($person, $state);
    }

    /**
     * Counts the of all male descendants.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_males($person, $state = 0) {
        $stack = array();
        $count = 0;
        array_push($stack, $person);

        while ($stack) {
            $p = array_pop($stack);
            $isMale = $p->isMale();
            switch ($state) {
                case self::$STATE_ALIVE:
                    $count += $isMale && !$p->IsDead ? 1 : 0;
                    break;

                case self::$STATE_DEAD:
                    $count += $isMale && $p->IsDead ? 1 : 0;
                    break;

                default:
                    $count += $isMale ? 1 : 0;
                    break;
            }

            foreach ($p->Sons() as $child) {
                array_push($stack, $child);
            }
        }

        return $count;
    }

    /**
     * Counts the of all female descendants.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_females($person, $state = 0) {
        $stack = array();
        $count = 0;
        array_push($stack, $person);

        while ($stack) {
            $p = array_pop($stack);
            $isFemale = $p->isFemale();
            switch ($state) {
                case self::$STATE_ALIVE:
                    $count += $isFemale && !$p->IsDead ? 1 : 0;
                    break;

                case self::$STATE_DEAD:
                    $count += $isFemale && $p->IsDead ? 1 : 0;
                    break;

                default:
                    $count += $isFemale ? 1 : 0;
                    break;
            }

            $count += self::count_daughters($p, $state);

            foreach ($p->Sons() as $child) {
                array_push($stack, $child);
            }
        }

        return $count;
    }

    /**
     * Counts the of sons.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_sons($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        $count = 0;

        foreach ($person->Sons() as $child) {
            switch ($state) {
                case self::$STATE_ALIVE:
//                    $count += !$child->IsDead && !$child->isClan() ? 1 : 0;
                    $count += !$child->IsDead ? 1 : 0;
                    break;

                case self::$STATE_DEAD:
//                    $count += $child->IsDead && !$child->isClan() ? 1 : 0;
                    $count += $child->IsDead ? 1 : 0;
                    break;

                default:
//                    $count += !$child->isClan() ? 1 : 0;
                    $count++;
                    break;
            }
        }

        return $count;
    }

    /**
     * Counts the of daughters.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_daughters($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        $count = 0;

        foreach ($person->Daughters() as $child) {
            switch ($state) {
                case self::$STATE_ALIVE:
                    $count += !$child->IsDead ? 1 : 0;
                    break;

                case self::$STATE_DEAD:
                    $count += $child->IsDead ? 1 : 0;
                    break;

                default:
                    $count ++;
                    break;
            }
        }

        return $count;
    }

}
