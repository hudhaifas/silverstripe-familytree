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
//                    'EventDate:GreaterThanOrEqual' => $person->BirthDate,
//                    'EventDate:LessThanOrEqual' => $person->DeathDate,
        ));
    }

    public static function update_all_related_events($person) {
        foreach ($person->RelatedEvents() as $event) {
            switch ($event->EventType) {
                case 'Birth':
                    $event->EventDate = self::get_birth_date($relative);
                    $event->DatePrecision = self::get_birth_date_precision($relative);
                    $event->EventPlace = $relative->BirthPlace;
                    break;

                case 'Death':
                    $event->EventDate = self::get_death_date($relative);
                    $event->DatePrecision = self::get_death_date_precision($relative);
                    $event->EventPlace = $relative->DeathPlace;
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
        switch ($type) {
            case 'Birth':
                $eventDate = self::get_birth_date($relative);
                break;

            case 'Death':
                $eventDate = self::get_death_date($relative);
                break;
        }

        if (!$eventDate) {
            var_dump('Event must have a valid date');
            return;
        }

        $event = PersonalEvent::get()->filter(array(
                    'PersonID' => $person->ID,
                    'RelatedPersonID' => $relative->ID,
                    'EventType' => $type,
                ))->first();

        if (!$event || !$event->exists()) {
            var_dump('New record');
            $event = new PersonalEvent();
            $event->PersonID = $person->ID;
            $event->RelatedPersonID = $relative->ID;
            $event->EventType = $type;
        } else {
            var_dump('Update record');
        }

        $event->EventTitle = self::get_event_title($type, $relation);
        $event->Relation = $relation;
        $event->EventDate = $eventDate;

        switch ($type) {
            case 'Birth':
                $event->DatePrecision = self::get_birth_date_precision($relative);
                $event->EventPlace = $relative->BirthPlace;
                break;

            case 'Death':
                $event->DatePrecision = self::get_death_date_precision($relative);
                $event->EventPlace = $relative->DeathPlace;
                break;
        }

//        $event->EventContent = self::generate_event_content($person, $relative, $type, $relation, $event->DatePrecision);

        $event->write();

        return $event;
    }

    public static function create_all_events($person) {
        self::create_relative_events($person, $person);
        self::create_relative_events($person, $person->Father(), 'Father');
        self::create_relative_events($person, $person->Mother(), 'Mother');

//        foreach ($person->Sons() as $son) {
//            self::create_relative_events($person, $son, 'Son');
//        }
//
//        foreach ($person->Daughters() as $daughter) {
//            self::create_relative_events($person, $daughter, 'Daughter');
//        }
//
//        if ($person->isMale()) {
//            foreach ($person->Wives() as $wife) {
//                self::create_relative_events($person, $wife, 'Wife');
//            }
//        }
//
//        if ($person->isFemale()) {
//            foreach ($person->Husbands() as $husband) {
//                self::create_relative_events($person, $husband, 'Husband');
//            }
//        }
    }

    public static function get_birth_date($person) {
        $date = new Date();

        if ($person->BirthDate) {
            $date->setValue($person->BirthDate);
        } else if ($person->Stats()->exists()) {
            $date->setValue('1/1/' . $person->Stats()->MinYear);
        } else {
            return null;
        }

        return $date->getValue();
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
        } else {
            return null;
        }

        return $date->getValue();
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

    private static function get_personal_date($person, $type, $isAccurate) {
        $date = $type == 'Birth' ? self::get_birth_date($person) : self::get_death_date($person);
        return $isAccurate ? $date : strtok($date, '-');
    }

    public static function generate_event_content($event, $person, $relative) {
        $content = '';

        $isAccurate = $event->DatePrecision == 'Accurate';

        $pronoun = $person->isFemale() ? 'SHE' : 'HE';
        $preposition = $isAccurate ? 'ON' : 'IN';

        if ($event->Relation == 'Self' && $event->EventType == 'Birth') {
            $name = $person->getShortName();

            $content .= _t("Genealogist.{$pronoun}_BORN_{$preposition}", '{name} was born on {date}', array(
                'name' => $name,
                'date' => $event->getDateValue()
            ));

            if ($event->EventPlace) {
                $content .= _t("Genealogist.IN_PLACE", " in {place}", array('place' => $event->EventPlace));
            }

            if ($person->Father()->exists()) {
                $content .= _t("Genealogist.BORN_TO", ' to {name}', array(
                    'name' => $person->Father()->getShortName(),
                ));

                $age = self::age_at_event(
                                GenealogistEventsHelper::get_birth_date($person->Father()), //
                                $event->EventDate
                );

                if ($age) {
                    $content .= _t("Genealogist.IN_AGE", '', array(
                        'age' => $age
                    ));
                }
            }

            if ($person->Mother()->exists()) {
                $content .= _t("Genealogist.BORN_AND", ' and {name}', array(
                    'name' => $person->Mother()->getShortName()
                ));

                $age = self::age_at_event(
                                GenealogistEventsHelper::get_birth_date($person->Mother()), //
                                $event->EventDate
                );

                if ($age) {
                    $content .= _t("Genealogist.IN_AGE", ', age {age}', array(
                        'age' => $age
                    ));
                }
            }
            ////////////////////
        } else if ($event->Relation == 'Self' && $event->EventType == 'Death') {
            $name = $person->getShortName();

            $content .= _t("Genealogist.{$pronoun}_DIED_{$preposition}", '{name} died on {date}', array(
                'name' => $name,
                'date' => $event->getDateValue()
            ));

            if ($event->EventPlace) {
                $content .= _t("Genealogist.IN_PLACE", " in {place}", array('place' => $event->EventPlace));
            }

            if ($event->Age) {
                $content .= _t("Genealogist.{$pronoun}_WAS_AGE", ", when he was {age}", array(
                    'age' => $event->Age
                ));
            }
        } else if ($event->Relation == 'Father' && $event->EventType == 'Death') {
            $name = $relative->getShortName();

            $content .= _t("Genealogist.{$pronoun}_FATHER_DIED_{$preposition}", 'His father {name} died on {date}', array(
                'name' => $name,
                'date' => $event->getDateValue()
            ));

            if ($event->EventPlace) {
                $content .= _t("Genealogist.IN_PLACE", " in {place}", array('place' => $event->EventPlace));
            }

            $age = self::age_at_event(
                            GenealogistEventsHelper::get_birth_date($person->Father()), //
                            $event->EventDate
            );

            if ($age) {
                $content .= _t("Genealogist.HE_WAS_AGE", ", when he was {age}", array(
                    'age' => $age
                ));
            }
        } else if ($event->Relation == 'Mother' && $event->EventType == 'Death') {
            $name = $relative->getShortName();

            $content .= _t("Genealogist.{$pronoun}_MOTHER_DIED_{$preposition}", 'His Mother {name} died on {date}', array(
                'name' => $name,
                'date' => $event->getDateValue()
            ));

            if ($event->EventPlace) {
                $content .= _t("Genealogist.IN_PLACE", " in {place}", array('place' => $event->EventPlace));
            }

            $age = self::age_at_event(
                            GenealogistEventsHelper::get_birth_date($person->Mother()), //
                            $event->EventDate
            );

            if ($age) {
                $content .= _t("Genealogist.SHE_WAS_AGE", ", when he was {age}", array(
                    'age' => $age
                ));
            }
        } else if ($event->Relation == 'Son' && $event->EventType == 'Birth') {
            
        } else if ($event->Relation == 'Son' && $event->EventType == 'Death') {
            
        }

        var_dump($event->EventTitle . ': ' . $content);
        return $content;
    }

    public static function age_at_event($birthDate, $eventDate) {
        if (!$eventDate || !$birthDate) {
            return false;
        }

        $birthObject = new DateTime($birthDate);
        $eventObject = new DateTime($eventDate);

        // This event is before person was born
        if ($eventObject <= $birthObject) {
//            echo('This event is before person was born');
            return 0;
        }
        $diff = $birthObject->diff($eventObject);
        $daysAgo = $diff->format('%a');

        if ($daysAgo < 30) {
            $span = round($daysAgo);
            return ($span != 1) ? "{$span} " . _t("Date.DAYS", "days") : "{$span} " . _t("Date.DAY", "day");
        } elseif ($daysAgo < 365) {
            $span = round($daysAgo / 30);
            return ($span != 1) ? "{$span} " . _t("Date.MONTHS", "months") : "{$span} " . _t("Date.MONTH", "month");
        } else {
            $span = round($daysAgo / 365);
//                return ($span != 1) ? "{$span} " . _t("Date.YEARS", "years") : "{$span} " . _t("Date.YEAR", "year");
        }

        return $span;
    }

}
