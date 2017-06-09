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
 * This class presents every clan or origin of a family in the genealogy tree.
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 2, 2016 - 11:56:42 AM
 */
class Clan
        extends Male {

    private static $db = array(
    );
    private static $has_one = array(
    );
    private static $has_many = array(
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
        "ClanTowns" => "Town",
    );
    private static $defaults = array(
        'IsPublicFigure' => 1,
    );

    public function getPersonName() {
        return $this->getAliasName();
    }

    /**
     * Returns the person's short name
     * @return string
     */
    public function getBriefName() {
        return "{$this->getClanName()} {$this->getTribeName()}";
    }

    /**
     * Returns the person's clan names
     * @return string
     */
    public function getClanName() {
        $childOf = _t('Genealogist.SONS_OF');
        $name = "{$childOf} {$this->getPersonName()}";

        if (!$this->Father()->exists()) {
            return $name;
        }

        return "{$name} {$this->Father()->getClanName()}";
    }

    public function isObjectDisabled() {
        return false;
    }

    public function getFirstName() {
        return $this->Name;
    }

    public function getObjectTabs() {
        $lists = parent::getObjectTabs();

        $townsCount = $this->ClanTowns()->Count();
        if ($townsCount) {
            $item = array(
                'Title' => _t('Genealogist.TOWNS', 'Towns') . " ({$townsCount})",
                'Content' => $this
                        ->customise(array(
                            'Results' => $this->ClanTowns()
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
