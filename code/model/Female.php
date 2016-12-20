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
 * This class presents every female in the genealogy tree.
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 2, 2016 - 11:05:49 AM
 */
class Female
        extends Person {

    private static $has_one = array(
        'Parent' => 'Person',
    );
    private static $has_many = array(
        'Children' => 'Person',
    );
    private static $belongs_many_many = array(
        'Husbands' => 'Male',
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
            $self->reorderField($fields, 'Comments', 'Root.Main', 'Root.Main');

            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');

            $self->reorderField($fields, 'Photo', 'Root.Main', 'Root.Details');
            $self->reorderField($fields, 'PageID', 'Root.Main', 'Root.Details');
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

}