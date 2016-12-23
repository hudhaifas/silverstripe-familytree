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

    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;
    private static $access_groups = array('administrators', 'librarians', 'genealogists');

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public static function is_genealogists() {
        $member = Member::currentUser();

        $groups = Config::inst()->get('GenealogistHelper', 'access_groups');
        return $member && $member->inGroups($groups);
    }

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

    public static function get_children($person) {
        $children = array();

        if ($person->Sons()->exists()) {
            foreach ($person->Sons() as $child) {
                $children[] = $child;
            }
        }

        if ($person->Daughters()->exists()) {
            foreach ($person->Daughters() as $child) {
                $children[] = $child;
            }
        }

        return (new ArrayList($children))
                        ->sort('BirthDate DESC')
                        ->sort('Created ASC')
//                        ->sort('BirthDate ASC')
        ;
    }

    /// Counts ///
    /**
     * Counts the of all offsprings
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_offspring($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        return self::count_males($person, $state) + self::count_females($person, $state);
    }

    /**
     * Counts the of all male offsprings
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_males($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        switch ($state) {
            case self::$STATE_ALIVE:
                $count = $person->isMale() && !$person->IsDead ? 1 : 0;
                break;

            case self::$STATE_DEAD:
                $count = $person->isMale() && $person->IsDead ? 1 : 0;
                break;

            default:
                $count = $person->isMale() ? 1 : 0;
                break;
        }

        foreach ($person->Sons() as $child) {
            $count += self::count_males($child, $state);
        }

        return $count;
    }

    /**
     * Counts the of all female offsprings
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public static function count_females($person, $state = 0) {
        if (!$person) {
            return 0;
        }

        switch ($state) {
            case self::$STATE_ALIVE:
                $count = $person->isFemale() && !$person->IsDead ? 1 : 0;
                break;

            case self::$STATE_DEAD:
                $count = $person->isFemale() && $person->IsDead ? 1 : 0;
                break;

            default:
                $count = $person->isFemale() ? 1 : 0;
                break;
        }

        $count += self::count_daughters($person, $state);

        foreach ($person->Sons() as $child) {
            $count += self::count_females($child, $state);
        }

        return $count;
    }

    /**
     * Counts the of sons
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
     * Counts the of daughters
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

    /// Functions ///
    public static function search_all_people($request, $term) {
        if (is_numeric($term)) {
            die('Numeric: ' . $term);
            return DataObject::get_by_id('Person', $term);
        }

        // to fetch books that's name contains the given search term
        $people = DataObject::get('Person')->filterAny(array(
            'IndexedName:PartialMatch' => $term,
            'Name:PartialMatch' => $term,
            'NickName:PartialMatch' => $term,
        ));

        return $people;
    }

    public static function add_father($personID, $fatherName) {
        $person = DataObject::get_by_id('Person', (int) $personID);

        echo 'Add parent (' . $fatherName . ') to: ' . $person->getTitle() . '<br />';

        $newFather = new Male();
        $newFather->Name = $fatherName;
        $newFather->FatherID = $person->FatherID;
        $newFather->write();

        $person->FatherID = $newFather->ID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function change_father($personID, $fatherID) {
        $person = DataObject::get_by_id('Person', (int) $personID);
        $father = DataObject::get_by_id('Person', (int) $fatherID);

        echo 'Change ' . $person->getTitle() . ' father to ' . $father->getTitle() . '<br />';

        $person->FatherID = $fatherID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function change_mother($personID, $methorID) {
        $person = DataObject::get_by_id('Person', (int) $personID);
        $methor = DataObject::get_by_id('Person', (int) $methorID);

        echo 'Change ' . $person->getTitle() . ' methor to ' . $methor->getTitle() . '<br />';

        $person->MotherID = $methorID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
        echo '&emsp;became: ' . $person->MotherID . '<br />';
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

    public static function delete_person($id, $reconnect = false) {
        $person = DataObject::get_by_id('Person', (int) $id);

        $person->delete();
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