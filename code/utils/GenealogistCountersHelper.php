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
 * @version 1.0, May 31, 2017 - 9:44:35 AM
 */
class GenealogistCountersHelper {

    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;
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

        $cachedCount = self::cache_counters_check('count-descendants', $person->ID, $state);
        if (isset($cachedCount)) {
            return $cachedCount;
        }

        $count = self::count_males($person, $state) + self::count_females($person, $state);

        return self::cache_counters_check('count-descendants', $person->ID, $state, $count);
    }

    /**
     * Counts the of all male descendants.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_males($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        $cachedCount = self::cache_counters_check('count-males', $person->ID, $state);
        if (isset($cachedCount)) {
            return $cachedCount;
        }

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

        return self::cache_counters_check('count-males', $person->ID, $state, $count);
    }

    /**
     * Counts the of all female descendants.
     *
     * @param Person $person person object
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_females($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        $cachedCount = self::cache_counters_check('count-females', $person->ID, $state);
        if (isset($cachedCount)) {
            return $cachedCount;
        }

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

        return self::cache_counters_check('count-females', $person->ID, $state, $count);
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

        $cachedCount = self::cache_counters_check('count-sons', $person->ID, $state);
        if (isset($cachedCount)) {
            return $cachedCount;
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

        return self::cache_counters_check('count-sons', $person->ID, $state, $count);
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

        $cachedCount = self::cache_counters_check('count-daughters', $person->ID, $state);
        if (isset($cachedCount)) {
            return $cachedCount;
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

        return self::cache_counters_check('count-daughters', $person->ID, $state, $count);
    }

    public static function cache_counters_check($counterType, $personID, $state, $result = null) {
        // This is the name used on the permission cache
        // converts something like 'CanEditType' to 'edit'.
        $cacheKey = strtolower($counterType) . "-$personID-$state";

        if (isset(self::$cache_counters[$cacheKey])) {
            $cachedValues = self::$cache_counters[$cacheKey];
            return $cachedValues;
        }

        self::$cache_counters[$cacheKey] = $result;

        return self::$cache_counters[$cacheKey];
    }

}
