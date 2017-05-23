<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, May 23, 2017 - 11:13:04 AM
 */
class Town
        extends DataObject {

    private static $db = array(
        'TownID' => 'Varchar(255)',
        'DefaultName' => 'Varchar(255)',
        'Latitude' => 'Varchar(255)',
        'Longitude' => 'Varchar(255)',
    );
    private static $has_one = array(
    );
    private static $has_many = array(
        'TwonNames' => 'TwonName',
        'Events' => 'PersonalEvent',
        'Births' => 'Person.BirthPlace',
        'Deaths' => 'Person.DeathPlace',
        'Buried' => 'Person.BurialPlace',
    );
    private static $many_many = array(
    );
    private static $defaults = array(
    );
    private static $summary_fields = array(
        'TownID',
        'Title',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Title'] = _t('Genealogist.TOWN_NAME', 'Town Name');
        $labels['TownID'] = _t('Genealogist.TOWN_ID', 'Town ID');
        $labels['DefaultName'] = _t('Genealogist.TOWN_DEFAULT_NAME', 'Default Name');
        $labels['TwonNames'] = _t('Genealogist.TWON_NAMES', 'Twon Names');
        $labels['Latitude'] = _t('Genealogist.LATITUDE', 'Latitude');
        $labels['Longitude'] = _t('Genealogist.LONGITUDE', 'Longitude');
        
        $labels['Events'] = _t('Genealogist.EVENTS', 'Events');
        $labels['Births'] = _t('Genealogist.BIRTHS', 'Births');
        $labels['Deaths'] = _t('Genealogist.DEATHS', 'Deaths');
        $labels['Buried'] = _t('Genealogist.BURIED', 'Buried');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getTitle($date = null) {
        if (!$date) {
//            $date = '1946-12-22';
            return $this->DefaultName ? $this->DefaultName : $this->TownID;
        }

        foreach ($this->TwonNames() as $townName) {
            if ($townName->isBetween($date)) {
                return $townName->Name;
            }
        }

        return $this->DefaultName ? $this->DefaultName : $this->TownID;
    }

}
