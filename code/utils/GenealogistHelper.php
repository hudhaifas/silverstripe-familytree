<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 10, 2016 - 3:13:22 PM
 */
class GenealogistHelper {

    public static function get_born_today($date = null) {
        return self::get_filtered_people('BirthDate', 'Anniversary', $date);
    }

    public static function get_born_this_year($date = null) {
        return self::get_filtered_people('BirthDate', 'Annual', $date);
    }

    public static function get_dead_today($date = null) {
        return self::get_filtered_people('DeathDate', 'Anniversary', $date);
    }

    public static function get_dead_this_year($date = null) {
        return self::get_filtered_people('DeathDate', 'Annual', $date);
    }

    public static function get_today_date() {
        $today = new Date();
        $time = SS_Datetime::now()->Format('U');
        $today->setValue($time);

        return $today->Nice();
    }

    public static function get_filtered_people($field, $filter, $date = null) {
        $people = Person::get();

        if (!$date) {
            $date = self::get_today_date();
        }

        $people = $people->filter(array(
            $field . ':' . $filter => $date
        ));

        return $people;
    }

    public static function get_root_clans() {
        return Clan::get()->filter(array('FatherID' => 0));
    }

}
