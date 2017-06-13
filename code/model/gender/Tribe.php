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
        extends Gender {

    private static $has_many = array(
        'Clans' => 'Male.Tribe'
    );
    private static $belongs_many_many = array(
        "TribeTowns" => "Town",
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if (!$this->ID) {
            return $fields;
        }

        // Clans
        $config = $this->personConfigs(false, false, false, false);

        $field = $fields->fieldByName('Root.Clans.Clans');
        $field->setConfig($config);

        return $fields;
    }

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

    public function canViewSons($member = false) {
        return true;
    }

    public function canViewDaughters($member = false) {
        return true;
    }

    public function ViewableSons() {
        $count = 0;
        return $count;
    }

    public function ViewableDaughters() {
        $count = 0;
        return $count;
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
        return $name;
    }

    public function getClansList() {
        return $this->Clans();
    }

    public function getAllBranchesList() {
        return GenealogistHelper::get_all_branches($this);
    }

    public function getObjectTabs() {
        $lists = parent::getObjectTabs();

        $townsCount = $this->TribeTowns()->Count();
        if ($townsCount) {
            $item = array(
                'Title' => _t('Genealogist.TOWNS', 'Towns') . " ({$townsCount})",
                'Content' => $this
                        ->customise(array(
                            'Results' => $this->TribeTowns()
                        ))
                        ->renderWith('List_Grid')
            );
            $lists->add($item);
        }

        $branches = $this->getAllBranchesList();
        if ($branches->count()) {
            $item = array(
                'Title' => _t('Genealogist.CLANS', 'Clans'),
                'Content' => $this
                        ->customise(array(
                            'Results' => $branches
                        ))
                        ->renderWith('List_Grid')
            );
            $lists->add($item);
        }
        $publicFigures = $this->getDescendantsPublicFigures();
        if ($publicFigures && $publicFigures->Count()) {
            $item = array(
                'Title' => _t('Genealogist.PUBLIC_FIGURES', 'Public Figures'),
                'Content' => $this
                        ->customise(array(
                            'Results' => $publicFigures
                        ))
                        ->renderWith('List_Grid')
            );
            $lists->add($item);
        }

        return $lists;
    }

}
