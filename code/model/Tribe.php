<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 29, 2017 - 10:35:15 AM
 */
class Tribe
        extends Clan {

    private static $has_many = array(
        'Clans' => 'Male.Tribe'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

//        $fields->removeFieldFromTab('Root.Main', 'TribeID');
//        $fields->removeFieldFromTab('Root', 'Children');
//        $fields->removeFieldFromTab('Root', 'Sons');
//        $fields->removeFieldFromTab('Root', 'Daughters');
        $fields->removeFieldFromTab('Root', 'Wives');
        $fields->removeFieldFromTab('Root.DatesTab', 'BirthDate');
        $fields->removeFieldFromTab('Root.DatesTab', 'BirthPlace');
        $fields->removeFieldFromTab('Root.DatesTab', 'BirthDateEstimated');
        $fields->removeFieldFromTab('Root.DatesTab', 'DeathDate');
        $fields->removeFieldFromTab('Root.DatesTab', 'DeathPlace');
        $fields->removeFieldFromTab('Root.DatesTab', 'DeathDateEstimated');
        $fields->removeFieldFromTab('Root.DatesTab', 'IsDead');
        $fields->removeFieldFromTab('Root.DatesTab', 'Age');
        $fields->removeFieldFromTab('Root.Main', 'FatherID');
        $fields->removeFieldFromTab('Root.Main', 'MotherID');

        return $fields;
    }

    public function getDescendantsLeaves() {
        $html = '';
        foreach ($this->Clans() as $child) {
            $html .= $child->getDescendants();
        }

        return $html;
    }

    /**
     * Returns the person's short name
     * @return string
     */
    public function getShortName() {
        return $this->getTribeName();
    }

    /**
     * Returns the person's short name
     * @return string
     */
    public function getBriefName() {
        return $this->getTribeName();
    }

    public function getTribeName() {
        $name = $this->getPersonName();
        if (!$this->Tribe()->exists()) {
            return $name;
        }

        return "{$name} " . $this->Tribe()->getTribeName();
    }

}
