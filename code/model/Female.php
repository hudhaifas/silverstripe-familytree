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

    private static $db = array(
        // Order
        'WifeOrder' => 'Int'
    );
    private static $has_one = array(
        'Parent' => 'Person',
    );
    private static $has_many = array(
        'Children' => 'Person',
    );
    private static $belongs_many_many = array(
        'Husbands' => 'Male',
    );
    private static $defaults = array(
        "CanViewType" => "OnlyTheseUsers",
    );

    public function canCreate($member = null) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $cachedPermission = self::cache_permission_check('create', $member, $this->ID);
        if (isset($cachedPermission)) {
            return $cachedPermission;
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        $extended = $this->extendedCan('canCreateFemale', $member);
        if ($extended !== null) {
            return $extended;
        }

        return false;
    }

    public function ViewableHusbands() {
        $flag = false;
        foreach ($this->Husbands() as $husband) {
            $flag = $flag || $husband->canView();
        }
        return $flag;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'WifeOrder');

        if (!$this->ID) {
            return $fields;
        }

        // Sons
        $config = $this->personConfigs(true, false, false);

        $field = $fields->fieldByName('Root.Sons.Sons');
        $field->setConfig($config);

        // Daughters
        $field = $fields->fieldByName('Root.Daughters.Daughters');
        $field->setConfig($config);

        // Children
        $field = $fields->fieldByName('Root.Children.Children');
        $field->setConfig($config);

        // Husbands
        $field = $fields->fieldByName('Root.Husbands.Husbands');
        $field->setConfig($this->personConfigs(true));

        return $fields;
    }

    /**
     * Returns the formated person's name
     * @return strnig
     */
    public function getFirstName() {
        $isTaskCaller = isset($GLOBALS['task_caller']) && $GLOBALS['task_caller'];
        return $this->canView() || $isTaskCaller ? $this->Name : _t('Genealogist.HIDDEN', 'Hidden');
    }

    public function getFullName($withChildOf = true) {
        $isTaskCaller = isset($GLOBALS['task_caller']) && $GLOBALS['task_caller'];
        return $this->canView() || $isTaskCaller ? parent::getFullName($withChildOf) : _t('Genealogist.HIDDEN', 'Hidden');
    }

    public function getAliasName() {
        $isTaskCaller = isset($GLOBALS['task_caller']) && $GLOBALS['task_caller'];
        return $this->canView() || $isTaskCaller ? parent::getAliasName() : _t('Genealogist.HIDDEN', 'Hidden');
    }

    public function getBriefName() {
        $isTaskCaller = isset($GLOBALS['task_caller']) && $GLOBALS['task_caller'];
        return $this->canView() || $isTaskCaller ? parent::getBriefName() : _t('Genealogist.HIDDEN', 'Hidden');
    }

    public function getShortName() {
        $isTaskCaller = isset($GLOBALS['task_caller']) && $GLOBALS['task_caller'];
        return $this->canView() || $isTaskCaller ? parent::getShortName() : _t('Genealogist.HIDDEN', 'Hidden');
    }

    public function getObjectDefaultImage() {
        return "genealogist/images/default-female.png";
    }

}
