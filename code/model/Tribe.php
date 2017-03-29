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
        'Clans' => 'Male'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

//        $fields->removeFieldFromTab('Root.Main', 'TribeID');
//        $fields->removeFieldFromTab('Root', 'Children');
        $fields->removeFieldFromTab('Root', 'Sons');
        $fields->removeFieldFromTab('Root', 'Daughters');
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

    public function getDescendantsLeaves($males = 1, $malesSeed = 1, $females = 0, $femalesSeed = 0) {
        if (isset($_GET['ancestral']) && $_GET['ancestral'] == 1) {
            return $this->getAncestorsLeaves();
        }

        if (isset($_GET['m'])) {
            $males = $_GET['m'];
        }

        if (isset($_GET['ms'])) {
            $malesSeed = $_GET['ms'];
        }

        if ($this->hasPermission()) {
            if (isset($_GET['f'])) {
                $females = $_GET['f'];
            }

            if (isset($_GET['fs'])) {
                $femalesSeed = $_GET['fs'];
            }
        }

        $html = <<<HTML
            <li class="{$this->CSSClasses()}" data-birth="{$this->CSSBirth()}" data-death="{$this->CSSDeath()}">
                <a href="#" title="{$this->getFullName()}" data-url="{$this->InfoLink()}" class="info-item">{$this->getPersonName()}</a>
                <ul>
                    {$this->getClansLeaves($males, $malesSeed, $females, $femalesSeed)}
                </ul>
            </li>
HTML;

        return $html;
    }

    private function getClansLeaves($males = 1, $malesSeed = 1, $females = 0, $femalesSeed = 0) {
        $html = '';

        foreach ($this->Clans() as $child) {
            $html .= $child->getDescendantsLeaves($males, $malesSeed, $females, $femalesSeed);
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
