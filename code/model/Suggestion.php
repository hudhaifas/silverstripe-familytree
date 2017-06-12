<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Suggestion on potential changes and information about person.
 * 
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Dec 13, 2016 - 11:25:26 AM
 */
class Suggestion
        extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'Email' => 'Varchar(255)',
        'Phone' => 'Varchar(255)',
        'Subject' => "Enum('General, Name, Father, Mother, Spouse, Sons, Daughters, BirthDate, DeathDate', 'Name')",
        'Message' => 'Text',
        'Proceeded' => 'Boolean',
    );
    private static $has_one = array(
        'Person' => 'Gender',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $defaults = array(
        "Proceeded" => 0,
    );
    private static $searchable_fields = array(
        'Message' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'Name',
        'Person.FullName',
        'Subject',
        'Message',
        'Created',
        'Proceeded',
    );
    private static $default_sort = 'Created';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('Genealogist.FROM', 'From');
        $labels['Email'] = _t('Genealogist.EMAIL', 'Email');
        $labels['Phone'] = _t('Genealogist.PHONE', 'Phone');
        $labels['Person.FullName'] = _t('Genealogist.NAME', 'Name');
        $labels['Subject'] = _t('Genealogist.SUBJECT', 'Subject');
        $labels['Message'] = _t('Genealogist.MESSAGE', 'Message');
        $labels['Person'] = _t('Genealogist.PERSON', 'Person');
        $labels['Created'] = _t('Genealogist.CREATED', 'Created');
        $labels['Proceeded'] = _t('Genealogist.PROCEEDED', 'Proceeded');

        return $labels;
    }

    public function canCreate($member = null) {
        return true;
    }

    public function canView($member = false) {
        return true;
    }

    public function canDelete($member = false) {
        return true;
    }

    public function canEdit($member = false) {
        return true;
    }

    public function getTitle() {
        return $this->Person()->getFullName();
    }

    public function getPersonName() {
        return $this->Person()->getAliasName();
    }

}
