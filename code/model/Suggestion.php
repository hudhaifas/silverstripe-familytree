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
    );
    private static $has_one = array(
        'Person' => 'Person',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
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
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Name'] = _t('Genealogist.FROM', 'From');
        $labels['Email'] = _t('Genealogist.EMAIL', 'Email');
        $labels['Phone'] = _t('Genealogist.PHONE', 'Phone');
        $labels['Person.FullName'] = _t('Genealogist.NAME', 'Name');
        $labels['Subject'] = _t('Genealogist.SUBJECT', 'Subject');
        $labels['Message'] = _t('Genealogist.MESSAGE', 'Message');
        $labels['Person'] = _t('Genealogist.PERSON', 'Person');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
//            $fields->removeFieldFromTab('Root.Main', 'ParentID');
//            $self->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'BirthDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'IsDead', 'Root.Main', 'Root.Main');
//            
//            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');
//            
//            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
        });

        $fields = parent::getCMSFields();

        return $fields;
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

    protected function onBeforeWrite() {
        parent::onBeforeWrite();
    }

    protected function onBeforeDelete() {
        parent::onBeforeDelete();
    }

    public function getTitle() {
        return $this->Person()->getFullName();
    }

    public function getPersonName() {
        return $this->Person()->getAliasName();
    }

}