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
 * This class presents every male in the genealogy tree.
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 2, 2016 - 11:05:40 AM
 */
class Male
        extends Person {

    private static $db = array(
        // Order
        'HusbandOrder' => 'Int'
    );
    private static $has_one = array(
        'Parent' => 'Person',
        'Clan' => 'Clan',
    );
    private static $has_many = array(
        'Children' => 'Person',
    );
    private static $many_many = array(
        'Wives' => 'Female',
    );
    static $many_many_extraFields = array(
        'Wives' => array(
            'MarriageDate' => 'Date'
        )
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

        $extended = $this->extendedCan('canCreateMale', $member);
        if ($extended !== null) {
            return $extended;
        }

        return false;
    }

    public function ViewableWives() {
        $flag = false;
        foreach ($this->Wives() as $wife) {
            $flag = $flag || $wife->canView();
        }
        return $flag;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'HusbandOrder');

        if (!$this->ID) {
            return $fields;
        }

        // Sons
        $config = $this->personConfigs();

        $field = $fields->fieldByName('Root.Sons.Sons');
        $field->setConfig($config);

        // Daughters
        $field = $fields->fieldByName('Root.Daughters.Daughters');
        $field->setConfig($config);

        // Children
        $field = $fields->fieldByName('Root.Children.Children');
        $config = $this->personConfigs();
        $config->addComponent(new GridFieldOrderableRows('ChildOrder'));
        $field->setConfig($config);

        // Wives
        $wifeFields = singleton('Female')->getCMSFields();
        $wifeFields->addFieldToTab(
                'Root.DatesTab',
                // The "ManyMany[<extradata-name>]" convention
                $dateField = new DateField('ManyMany[MarriageDate]', _t('Genealogist.MARRIAGEDATE', 'Marriage Date'))
        );

        $dateField->setConfig('showcalendar', true);
        $dateField->setConfig('dateformat', 'dd-MM-yyyy');

        $config = $this->personConfigs(true, true, true, true);
        $config->addComponent(new GridFieldOrderableRows('WifeOrder'));
        $config->getComponentByType('GridFieldDetailForm')->setFields($wifeFields);

        $gridField = new GridField('Wives', 'Wives', $this->Wives(), $config);
        $fields->findOrMakeTab('Root.Wives')->replaceField('Wives', $gridField);

        return $fields;
    }

    public function getClanName() {
        $cachedName = self::cache_name_check('clan-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = '';

        if ($this->Clan()->exists()) {
            $name .= $this->Clan()->getClanName();
        }

        if ($this->Father()->exists() && $this->Father()->getClanName()) {
            $name .= $this->Father()->getClanName();
        }

        return self::cache_name_check('clan-name', $this->ID, $name);
    }

    public function getObjectDefaultImage() {
        return "genealogist/images/default-male.png";
    }

}
