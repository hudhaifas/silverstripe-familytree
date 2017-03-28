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
 * @version 1.0, Mar 27, 2017 - 3:43:56 PM
 */
class GenealogistEventsHelper {

    public static function get_life_events($person) {
        return $person->Events()->filter(array(
//                    'Date:GreaterThanOrEqual' => $person->BirthDate,
//                    'Date:LessThanOrEqual' => $person->DeathDate,
        ));
    }

    public static function update_all_related_events($person) {
        foreach ($person->RelatedEvents() as $event) {
            switch ($event->Type) {
                case 'Birth':
                    $event->Date = self::get_birth_date($person);
                    break;

                case 'Death':
                    $event->Date = self::get_death_date($person);
                    break;

                default:
                    break;
            }

            $event->write();
        }
    }

    public static function create_relative_events($person, $relative, $realtion = 'Self') {
        if ($person->exists() && $relative->exists()) {
            self::create_event($person, $relative, 'Birth', $realtion);

            if ($relative->IsDead) {
                self::create_event($person, $relative, 'Death', $realtion);
            }
        }
    }

    public static function create_event($person, $relative, $type = 'Custom', $relation) {
        $event = PersonalEvent::get()->filter(array(
                    'PersonID' => $person->ID,
                    'RelatedPersonID' => $relative->ID,
                    'Type' => $type,
                ))->first();

        if (!$event || !$event->exists()) {
            echo('New record');
            $event = new PersonalEvent();
            $event->PersonID = $person->ID;
            $event->RelatedPersonID = $relative->ID;
            $event->Type = $type;
        } else {
            echo('Update record');
        }

        $event->Title = self::get_event_title($type, $relation);

        switch ($event->Type) {
            case 'Birth':
                $event->Date = self::get_birth_date($relative);
                $event->DatePrecision = self::get_birth_date_precision($relative);
                $event->Location = $relative->BirthPlace;
                break;

            case 'Death':
                $event->Date = self::get_death_date($relative);
                $event->DatePrecision = self::get_death_date_precision($relative);
                $event->Location = $relative->DeathPlace;
                break;
        }
        $event->write();

        return $event;
    }

    public static function create_all_events($person) {
        self::create_relative_events($person, $person);
        self::create_relative_events($person, $person->Father(), 'Father');
        self::create_relative_events($person, $person->Mother(), 'Mother');

        foreach ($person->Sons() as $son) {
            self::create_relative_events($person, $son, 'Son');
        }

        foreach ($person->Daughters() as $daughter) {
            self::create_relative_events($person, $daughter, 'Daughter');
        }

        if ($person->isMale()) {
            foreach ($person->Wives() as $wife) {
                self::create_relative_events($person, $wife, 'Wife');
            }
        }

        if ($person->isFemale()) {
            foreach ($person->Husbands() as $husband) {
                self::create_relative_events($person, $husband, 'Husband');
            }
        }
    }

    public static function get_birth_date($person) {
        $date = new Date();

        if ($person->BirthDate) {
            $date->setValue($person->BirthDate);
        } else if ($person->Stats()->exists()) {
            $date->setValue('1/1/' . $person->Stats()->MinYear);
        }

        return $date;
    }

    public static function get_birth_date_precision($person) {
        if ($person->BirthDate && !$person->BirthDateEstimated) {
            return 'Accurate';
        } else if ($person->BirthDate && $person->BirthDateEstimated) {
            return 'Estimated';
        } else {
            return 'Calculated';
        }
    }

    public static function get_death_date($person) {
        $date = new Date();

        if ($person->DeathDate) {
            $date->setValue($person->DeathDate);
        } else if ($person->Stats()->exists()) {
            $date->setValue('1/1/' . $person->Stats()->MaxYear);
        }

        return $date;
    }

    public static function get_death_date_precision($person) {
        if ($person->DeathDate && !$person->DeathDateEstimated) {
            return 'Accurate';
        } else if ($person->DeathDate && $person->DeathDateEstimated) {
            return 'Estimated';
        } else {
            return 'Calculated';
        }
    }

    public static function get_event_title($type, $relation) {
        return $type . '_' . $relation;
    }

    public static function age_at($startDate, $endDate) {
        if (!$endDate->value) {
            return false;
        }

        if ($startDate && $startDate->value) {
            $time = $startDate->Format('U');
        } else {
            $time = SS_Datetime::now()->Format('U');
        }

        $ago = abs($time - strtotime($endDate->value));
        $significance = 2;

        if ($ago < $significance * 86400 * 30) {
            return self::age_at_format('days', $startDate, $endDate);
        } elseif ($ago < $significance * 86400 * 365) {
            return self::age_at_format('months', $startDate, $endDate);
        } else {
            return self::age_at_format('years', $startDate, $endDate);
        }
    }

    public static function age_at_format($format, $startDate, $endDate) {
        if (!$endDate->value) {
            return false;
        }

        if ($startDate && $startDate->value) {
            $time = $startDate->Format('U');
        } else {
            $time = SS_Datetime::now()->Format('U');
        }
        $ago = abs($time - strtotime($endDate->value));

        switch ($format) {
            case "days":
                $span = round($ago / 86400);
                return ($span != 1) ? "{$span} " . _t("Date.DAYS", "days") : "{$span} " . _t("Date.DAY", "day");

            case "months":
                $span = round($ago / 86400 / 30);
                return ($span != 1) ? "{$span} " . _t("Date.MONTHS", "months") : "{$span} " . _t("Date.MONTH", "month");

            case "years":
                $span = round($ago / 86400 / 365);
//                return ($span != 1) ? "{$span} " . _t("Date.YEARS", "years") : "{$span} " . _t("Date.YEAR", "year");
        }

        return $span;
    }

}
