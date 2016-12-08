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
 * @version 1.0, Nov 2, 2016 - 11:56:42 AM
 */
class Clan
        extends Male {

    private static $db = array(
        'Overview' => 'Text',
    );
    private static $has_one = array(
    );
    private static $has_many = array(
    );
    private static $many_many = array(
        'Towns' => 'Town',
    );

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

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Overview'] = _t('FamilyTree.OVERVIEW', 'Overview');
        $labels['Towns'] = _t('FamilyTree.TOWNS', 'Towns');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            $self->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'Note', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'BirthDate', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'IsDead', 'Root.Main', 'Root.Main');

            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'WifeID', 'Root.Main', 'Root.Main');

            $self->reorderField($fields, 'TwonsID', 'Root.Main', 'Root.Details');
            $self->reorderField($fields, 'PageID', 'Root.Main', 'Root.Details');
            $self->reorderField($fields, 'Overview', 'Root.Main', 'Root.Details');

//            $fields->removeFieldFromTab('Root', 'Towns');
//            $twonField = TagField::create(
//                            'Towns', //
//                            _t('FamilyTree.TOWNS', 'Towns'), //
//                            Town::get(), //
//                            $self->Towns()
//            );
//            $fields->addFieldToTab('Root.Details', $twonField);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getPersonName() {
        return $this->getAliasName();
    }

}