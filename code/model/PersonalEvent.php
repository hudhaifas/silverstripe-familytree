<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 27, 2017 - 1:33:25 PM
 */
class PersonalEvent
        extends DataObject {

    private static $db = array(
        'Title' => 'Varchar(255)',
        'Date' => 'Date',
        'DatePrecision' => 'Enum("Accurate, Estimated, Calculated", "Accurate")',
        'Location' => 'Varchar(255)',
        'Age' => 'Int',
        'Content' => 'HTMLText',
        'IsPrivate' => 'Boolean',
        'IsAuto' => 'Boolean',
        'Type' => 'Enum("Birth, Death, Marriage, Resident, Graduated, Custom", "Custom")',
    );
    private static $has_one = array(
        'Person' => 'Person',
        'RelatedPerson' => 'Person',
    );
    private static $default_sort = 'Date';

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if ($field = $fields->fieldByName('Root.Main.Date')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }
        return $fields;
    }

    public function getYear() {
        return $this->Date()->Year();
    }

    public function getPreYear() {
        return $this->Date()->Year();
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        if ($this->Date) {
//            $this->Age = $this->Date - $this->Person()->CSSBirth();
        }
    }

}
