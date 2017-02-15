<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonCMSExtension
 *
 * @author hshatnawi
 */
class PersonCMSExtension extends DataExtension {

    public function personConfigs($showFather = false, $showMother = true, $allowCreate = true) {
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldPaginator(15));
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldToolbarHeader());
        $config->addComponent(new GridFieldTitleHeader());
        $config->addComponent(new GridFieldFilterHeader());
        if ($allowCreate) {
            $config->addComponent(new GridFieldAddNewInlineButton());
        }
        $config->addComponent(new GridFieldAddExistingAutocompleter('buttons-before-right', array('IndexedName', 'Name')));
        $config->addComponent(new GridFieldDetailForm());
//        $config->addComponent(new GridFieldAddNewMultiClass());
//        $config->addComponent(new GridFieldAddNewButton());

        $columns = array();
        $columns['Name'] = array(
            'title' => _t('Genealogist.NAME', 'Name'),
            'field' => 'TextField'
        );
        $columns['NickName'] = array(
            'title' => _t('Genealogist.NICKNAME', 'NickName'),
            'field' => 'TextField'
        );
        $columns['IsDead'] = array(
            'title' => _t('Genealogist.ISDEAD', 'Is Dead'),
            'field' => 'CheckboxField'
        );

        if ($showFather) {
            $columns['Parents'] = array(
                'title' => _t('Genealogist.FATHER_NAME', 'Father Name'),
                'callback' => function($record, $column, $grid) {
                    $field = ReadonlyField::create($column);
                    $father = $record->getParents();
                    $field->setValue($father);
                    return $field;
                }
            );
        }

        if ($showMother) {
            $columns['MotherID'] = array(
                'title' => _t('Genealogist.MOTHER_NAME', 'Mother Name'),
                'callback' => function($record, $column, $grid) {
                    if ($record->Father()->exists()) {
                        $mothers = $record->Father()->Wives()->map();
                        return DropdownField::create($column)
                                        ->setSource($mothers)
                                        ->setValue($record->MotherID)
                                        ->setEmptyString(_t('Genealogist.CHOOSE_MOTHER', 'Choose Mother'));
                    }

                    return ReadonlyField::create($column);
                }
            );
        }

        $columns['BirthDate'] = array(
            'title' => _t('Genealogist.BIRTHDATE', 'Birth Date'),
            'callback' => function($record, $column, $grid) {
                $field = DateField::create($column);
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
                return $field;
            }
        );
        $columns['BirthDateEstimated'] = array(
            'field' => 'CheckboxField'
        );
        $columns['DeathDate'] = array(
            'title' => _t('Genealogist.DEATHDATE', 'Death Date'),
            'callback' => function($record, $column, $grid) {
                $field = DateField::create($column);
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
                return $field;
            }
        );
        $columns['DeathDateEstimated'] = array(
            'field' => 'CheckboxField'
        );
        $columns['Note'] = array(
            'title' => _t('Genealogist.NOTE', 'Note'),
            'field' => 'TextField'
        );

        $edit = new GridFieldEditableColumns();
        $edit->setDisplayFields($columns);

        $config->addComponent($edit);

        $config->addComponent(new GridFieldExternalLink());
        $config->addComponent(new GridFieldEditButton());
        $config->addComponent(new GridFieldDeleteAction(true));

        return $config;
    }

    public function getExternalLinkText() {
        $title = _t('Genealogist.SHOW_THIS', 'Show this person tree');
        return "<img src='genealogist/images/genealogy.png' title='$title'>";
    }

    function getExternalLink($action = null) {
        return $this->owner->personLink($this->owner->ID);
    }

}
