<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Feb 1, 2017 - 1:15:17 PM
 */
class PersonalExtension
        extends DataExtension {

    private static $many_many = array(
        'People' => 'Person',
    );

    public function extraTabs(&$lists) {
        $people = $this->owner->People();
        if ($people->Count()) {
            $lists[] = array(
                'Title' => _t('Genealogist.PEOPLE', 'People'),
                'Content' => $this->owner
                        ->customise(array(
                            'Results' => $people
                        ))
                        ->renderWith('List_Grid')
            );
        }
    }

    public function updateCMSFields(FieldList $fields) {
        $field = $fields->fieldByName('Root.People.People');
        if ($field != null) {
//        $config = GridFieldConfig::create();
            $config = $field->getConfig();

            $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
            $config->addComponent(new GridFieldAddExistingAutocompleter('buttons-before-right', array('IndexedName', 'Name')));

            $field->setConfig($config);
        }
    }

}
