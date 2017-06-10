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
 * @version 1.0, Mar 29, 2017 - 10:35:15 AM
 */
class Tribe
        extends Clan {

    private static $has_many = array(
        'Clans' => 'Male.Tribe'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if (!$this->ID) {
            return $fields;
        }

        // Clans
        $config = $this->personConfigs();

        $field = $fields->fieldByName('Root.Clans.Clans');
        $field->setConfig($config);

//        $fields->removeFieldFromTab('Root.Main', 'TribeID');
//        $fields->removeFieldFromTab('Root', 'Children');
//        $fields->removeFieldFromTab('Root', 'Sons');
//        $fields->removeFieldFromTab('Root', 'Daughters');
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

    public function getDescendantsLeaves() {
        $html = '';
        foreach ($this->Clans() as $child) {
            $html .= $child->getDescendants();
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

    public function getClansList() {
        return $this->Clans();
    }

}
