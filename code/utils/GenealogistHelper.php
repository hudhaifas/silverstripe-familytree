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

    /// Filters ///
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

    /// Getters ///
    public static function get_all_clans() {
        return Clan::get();
    }

    public static function get_root_clans() {
        return Clan::get()->filter(array('FatherID' => 0));
    }

    public static function get_person($id) {
        return DataObject::get_by_id('Person', (int) $id);
    }

    /// Functions ///
    public static function search_all_people($request, $term) {
        if (is_numeric($term)) {
            die('Numeric: ' . $term);
            return DataObject::get_by_id('Person', $term);
        }

        // to fetch books that's name contains the given search term
        $people = DataObject::get('Person')->filterAny(array(
            'Name:PartialMatch' => $term,
            'NickName:PartialMatch' => $term,
        ));

        return $people;
    }

    public static function add_daughters($id, $names, $delimiter = "|") {
        $parent = DataObject::get_by_id('Person', (int) $id);
        $namesList = explode($delimiter, $names);

        echo 'Add ' . count($namesList) . ' daughters to: ' . $parent->getTitle() . '<br />';

        foreach ($namesList as $name) {
            $daughter = new Female();
            $daughter->Name = $name;
            $daughter->FatherID = $id;
            $daughter->write();

            echo '&emsp;Daughter: ' . $name . ' has ID: ' . $daughter->ID . '<br />';
        }
    }

    public static function add_sons($id, $names, $delimiter = "|") {
        $parent = DataObject::get_by_id('Person', (int) $id);
        $namesList = explode($delimiter, $names);

        echo 'Add ' . count($namesList) . ' sons to: ' . $parent->getTitle() . '<br />';

        foreach ($namesList as $name) {
            $son = new Male();
            $son->Name = $name;
            $son->FatherID = $id;
            $son->write();

            echo '&emsp;Sone: ' . $name . ' has ID: ' . $son->ID . '<br />';
        }
    }

    public static function add_parent($id, $name) {
        $person = DataObject::get_by_id('Person', (int) $id);

        echo 'Add parent (' . $name . ') to: ' . $person->getTitle() . '<br />';

        $parent = new Male();
        $parent->Name = $name;
        $parent->FatherID = $person->FatherID;
        $parent->write();

        $person->FatherID = $parent->ID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function delete_person($id, $reconnect = false) {
        $person = DataObject::get_by_id('Person', (int) $id);

        $person->delete();
    }

    public static function change_parent($personID, $parentID) {
        $person = DataObject::get_by_id('Person', (int) $personID);
        $parent = DataObject::get_by_id('Person', (int) $parentID);

        echo 'Change ' . $person->getTitle() . ' parent to ' . $parent->getTitle() . '<br />';

        $person->FatherID = $parent->ID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function suggest_change($name, $email, $phone, $personID, $subject, $message) {
        $suggestion = new Suggestion();

        $suggestion->PersonID = $personID;
        $suggestion->Name = $name;
        $suggestion->Email = $email;
        $suggestion->Phone = $phone;
        $suggestion->Subject = $subject;
        $suggestion->Message = $message;
        $suggestion->write();
    }

}