<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, May 23, 2017 - 11:13:58 AM
 */
class TwonName
        extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'StartDate' => 'Date',
        'EndDate' => 'Date',
        'Note' => 'Text',
    );
    private static $has_one = array(
        'Town' => 'Town',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $defaults = array(
    );
    private static $summary_fields = array(
        'Name',
        'Town.Title',
        'StartDate',
        'EndDate',
        'Note',
    );
    private static $default_sort = 'StartDate';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('Genealogist.NAME', 'Name');
        $labels['Town.Title'] = _t('Genealogist.TOWN_NAME', 'Town Name');
        $labels['StartDate'] = _t('Genealogist.START_DATE', 'Start Date');
        $labels['EndDate'] = _t('Genealogist.END_DATE', 'End Date');
        $labels['Note'] = _t('Genealogist.NOTE', 'Note');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if ($field = $fields->fieldByName('Root.Main.StartDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }

        if ($field = $fields->fieldByName('Root.Main.EndDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }

        return $fields;
    }

    public function isBetween($date) {
        if (!$date || !$this->StartDate) {
            return false;
        }

        $endDate = $this->EndDate ? $this->EndDate : date('Y-m-d');

        if ($date > $this->StartDate && $date < $endDate) {
            return true;
        }

        return false;
    }

}
