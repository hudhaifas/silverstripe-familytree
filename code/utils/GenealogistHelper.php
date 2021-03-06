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
 * @version 1.0, Nov 10, 2016 - 3:13:22 PM
 */
class GenealogistHelper {

    private static $access_groups = array('administrators', 'librarians', 'genealogists', 'co-genealogists');

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public static function is_genealogists() {
        if (isset($GLOBALS['task_caller']) && $GLOBALS['task_caller']) {
            return true;
        }

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
    public static function get_all_clans_and_branches() {
        $list = array_merge(DataObject::get('Clan')->toArray(), DataObject::get('Branch')->toArray());
        return new ArrayList($list);
    }

    public static function get_main_clans_and_branches() {
        $list = array_merge(
                DataObject::get('Clan')->toArray(), //
                DataObject::get('Branch')->filterAny(array(
                    'FatherID' => 0,
                    'IsClan' => 1
                ))->toArray()
        );
        return new ArrayList($list);
    }

    public static function get_gender($id) {
        return DataObject::get_by_id('Gender', (int) $id);
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

        return (new ArrayList($children));
    }

    public static function get_all_descendants($gender) {
        if (!$gender) {
            return null;
        }

        return DataObject::get('Person')->filter(array(
                    'IndexedAncestors:PartialMatch' => "|{$gender->ID}|"
                ))->Sort('YearOrder ASC');
    }

    public static function get_all_branches($gender) {
        if (!$gender) {
            return null;
        }

        return DataObject::get('Branch')->filter(array(
                    'IndexedAncestors:PartialMatch' => "|{$gender->ID}|"
                ))->Sort('YearOrder ASC');
    }

    public static function get_descendants_public_figures($gender) {
        if (!$gender) {
            return null;
        }

        return DataObject::get('Person')->filter(array(
                    'IndexedAncestors:PartialMatch' => "|{$gender->ID}|",
                    'IsPublicFigure' => 1,
                ))->Sort('YearOrder ASC');
    }

    /// Find kinships between two persons ///
    /**
     * Returns a list of all ancestors ID's
     *
     * @param Person $person1
     * @param Person $person2
     */
    public static function get_kinships($person1, $person2) {
        if (!$person1 || !$person2) {
            return null;
        }

        $ancestors1 = self::get_ancestors($person1);
        $ancestors2 = self::get_ancestors($person2);

        $common = self::get_common_ancestors($ancestors1, $ancestors2);

        $kinships = self::get_kinship_lists($common, $ancestors1, $ancestors2);

        return $kinships;
    }

    /**
     * Returns a list of all ancestors ID's
     *
     * @param Person $person
     */
    private static function get_ancestors($person) {
        $stack = array();
        array_push($stack, $person);

        $ancestors = array();
        $paths = array();
        $paths[$person->ID] = '';

        while ($stack) {
            $p = array_pop($stack);

            if (in_array($p->ID, $ancestors)) {
                continue;
            }

            $ancestors[] = $p->ID;

            if (!$p->isClan()) {
                $mother = $p->Mother();
                if ($mother && $mother->exists()) {
                    array_push($stack, $mother);
                    $paths[$mother->ID] = $p->ID . ',' . $paths[$p->ID];
                }

                $father = $p->Father();
                if ($father && $father->exists()) {
                    array_push($stack, $father);
                    $paths[$father->ID] = $p->ID . ',' . $paths[$p->ID];
                }
            } else {
//                array_push($stack, $p);
            }

            if ($p->isMale()) {
                $clan = $p->Clan();
                if ($clan && $clan->exists()) {
                    array_push($stack, $clan);
                    $paths[$clan->ID] = $p->ID . ',' . $paths[$p->ID];
                }
            }
        }

        return array($ancestors, $paths);
    }

    /**
     * Returns a list of all fathers ID's
     *
     * @param Person $person
     */
    public static function get_ancestors_ids($person) {
        $stack = array();
        array_push($stack, $person);

        $ancestors_ids = array();

        while ($stack) {
            $p = array_pop($stack);

            if (in_array($p->ID, $ancestors_ids)) {
                continue;
            }

            $ancestors_ids[] = $p->ID;

            $father = $p->Father();
            if ($father && $father->exists()) {
                array_push($stack, $father);
            }

            if ($p->isMale()) {
                $clan = $p->Clan();
                if ($clan && $clan->exists()) {
                    $ancestors_ids[] = $clan->ID;
                }
            }
        }

        return $ancestors_ids;
    }

    /**
     * Returns a list of all ancestors ID's
     *
     * @param array $ancestors1 ancestors list of a person
     * @param array $ancestors2 ancestors list of another person
     */
    private static function get_common_ancestors($ancestors1, $ancestors2) {
        $intersect = array_intersect($ancestors1[0], $ancestors2[0]);
        $toUnset = array();

        foreach ($intersect as $c1) {
            foreach ($intersect as $c2) {
                if (self::is_cild_of($c1, $c2)) {
//                    $toUnset[] = $c2;
                }
            }
        }

        $common = array_diff($intersect, $toUnset);
//        var_dump($common);

        return $common;
    }

    private static function get_kinship_lists($common, $ancestors1, $ancestors2) {
        $kinships = array();

        foreach ($common as $id) {
            $list1 = explode(',', $ancestors1[1][$id]);
            $list2 = explode(',', $ancestors2[1][$id]);

            // Find common children in both trees
            while (isset($list1[0], $list2[0]) && $list1[0] == $list2[0]) {
                $id = $list1[0];
                array_shift($list1);
                array_shift($list2);
            }

            $k = array();
            $k[] = self::get_gender($id);
            $k[] = self::create_kinship($id, $list1);
            $k[] = self::create_kinship($id, $list2);

            $kinships[$id] = $k;
//            var_dump($kinships[$id]);
//            $kinships[] = self::create_kinship($id, $list1);
//            $kinships[] = self::create_kinship($id, $list2);
        }

        return $kinships;
    }

    private static function create_kinship($id, $list) {
        $series = array();
//        $series[] = self::get_gender($id);
        foreach ($list as $value) {
            $series[] = self::get_gender($value);
        }

        return $series;
    }

    private static function is_cild_of($id1, $id2) {
        $p1 = self::get_gender($id1);

        return ($p1->FatherID == $id2 || $p1->MotherID == $id2);
    }

    /// Functions ///
    public static function search_all_people($request, $term) {
        if (is_numeric($term)) {
            die('Numeric: ' . $term);
            return DataObject::get_by_id('Gender', $term);
        }

        // to fetch books that's name contains the given search term
        $people = DataObject::get('Gender')
                ->filterAny(array(
                    'IndexedName:PartialMatch' => $term,
                    'Name:PartialMatch' => $term,
                    'NickName:PartialMatch' => $term,
                ))
                ->sort('CHAR_LENGTH(IndexedName) ASC');

        return $people;
    }

    public static function add_father($personID, $fatherName) {
        $person = DataObject::get_by_id('Gender', (int) $personID);

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
        $person = DataObject::get_by_id('Gender', (int) $personID);
        $father = DataObject::get_by_id('Gender', (int) $fatherID);

        echo 'Change ' . $person->getTitle() . ' father to ' . $father->getTitle() . '<br />';

        $person->FatherID = $fatherID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function change_mother($personID, $methorID) {
        $person = DataObject::get_by_id('Gender', (int) $personID);
        $methor = DataObject::get_by_id('Gender', (int) $methorID);

        echo 'Change ' . $person->getTitle() . ' methor to ' . $methor->getTitle() . '<br />';

        $person->MotherID = $methorID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
        echo '&emsp;became: ' . $person->MotherID . '<br />';
    }

    public static function add_daughters($id, $names, $delimiter = "|") {
        $parent = DataObject::get_by_id('Gender', (int) $id);
        $namesList = explode($delimiter, $names);

        echo 'Add ' . count($namesList) . ' daughters to: ' . $parent->getTitle() . '<br />';

        foreach ($namesList as $name) {
            if (!$name || strlen($name) == 0) {
                continue;
            }

            $daughter = new Female();
            $daughter->Name = $name;
            $daughter->FatherID = $id;
            $daughter->write();

            echo '&emsp;Daughter: ' . $name . ' has ID: ' . $daughter->ID . '<br />';
        }
    }

    public static function add_sons($id, $names, $delimiter = "|") {
        $parent = DataObject::get_by_id('Gender', (int) $id);
        $namesList = explode($delimiter, $names);

        echo 'Add ' . count($namesList) . ' sons to: ' . $parent->getTitle() . '<br />';

        foreach ($namesList as $name) {
            if (!$name || strlen($name) == 0) {
                continue;
            }

            $son = new Male();
            $son->Name = $name;
            $son->FatherID = $id;
            $son->write();

            echo '&emsp;Sone: ' . $name . ' has ID: ' . $son->ID . '<br />';
        }
    }

    public static function add_spouse($id, $spouseID = null, $spouseName = null) {
        $person = DataObject::get_by_id('Gender', (int) $id);
        $isMale = $person->isMale();

        if ($spouseID) {
            $spouce = DataObject::get_by_id('Gender', (int) $spouseID);
        }

        if (!$spouce) {
            $spouce = $isMale ? new Female() : new Male();
            $spouce->Name = $spouseName ? $spouseName : ($isMale ? _t('Genealogist.WIFE', 'Wife') : _t('Genealogist.HUSBAND', 'Husband'));
            $spouce->write();
        }

        if ($isMale) {
            $person->Wives()->add($spouce);
        } else {
            $person->Husbands()->add($spouce);
        }
        $person->write();
    }

    public static function delete_person($id, $reconnect = false) {
        $person = DataObject::get_by_id('Gender', (int) $id);

        $person->delete();
    }

    /**
     * If the person has only one wife, then assign this wife as a mother of all his children
     * @param type $id
     */
    public static function single_wife($id) {
        $person = DataObject::get_by_id('Gender', (int) $id);

        if ($person->isMale() && $person->Wives()->Count() == 1) {
            $wife = $person->Wives()->first();

            foreach ($person->Children() as $child) {
                $child->MotherID = $wife->ID;
                $child->write();
            }
        }
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
