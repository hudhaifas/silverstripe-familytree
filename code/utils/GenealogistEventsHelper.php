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
                    'Date:GreaterThanOrEqual' => $person->BirthDate,
                    'Date:LessThanOrEqual' => $person->DeathDate,
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

    public static function create_relative_events($person, $relative) {
        if ($person->exists() && $relative->exists()) {
            self::create_event($person, $relative, 'Birth');

            if ($relative->isDead) {
                self::create_event($person, $relative, 'Death');
            }
        }
    }

    public static function create_event($person, $relative, $type = 'Custom') {
        $current = PersonalEvent::get()->filter(array(
            'PersonID' => $person->ID,
            'RelatedPersonID' => $relative->ID,
            'Type' => $type,
        ));

        echoln($current);
        if ($current) {
            
            return;
        }

        $event = new PersonalEvent();
        $event->PersonID = $person->ID;
        $event->RelatedPersonID = $relative->ID;
        $event->Title = 'Test';
        $event->Type = $type;

        switch ($event->Type) {
            case 'Birth':
                $event->Date = self::get_birth_date($relative);
                break;

            case 'Death':
                $event->Date = self::get_death_date($relative);
                break;
        }
        $event->write();

        return $event;
    }

    public static function create_all_events($person) {
        self::create_relative_events($person, $person->Father());
//        self::create_relative_events($person, $person->Mother());
//
//        foreach ($person->Children() as $child) {
//            self::create_relative_events($person, $child);
//        }
    }

    public static function get_birth_date($person) {
        $date = new Date();

        if ($person->BirthDate) {
            $date->setValue($person->BirthDate);
        } else if ($person->Stats()->exists()) {
            $date->setValue($person->Stats()->MinYear);
        }

        return $date;
    }

    public static function get_death_date($person) {
        $date = new Date();

        if ($person->DeathDate) {
            $date->setValue($person->DeathDate);
        } else if ($person->Stats()->exists()) {
            $date->setValue($person->Stats()->MaxYear);
        }

        return $date;
    }

}
