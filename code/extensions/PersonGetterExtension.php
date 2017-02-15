<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonGetterEx
 *
 * @author hshatnawi
 */
class PersonGetterExtension extends DataExtension {



    public function getFirstName() {
//        return $this->hasPermission() || !$this->IsPrivate ? $this->Name : _t('Genealogist.HIDDEN', 'Hidden');
        return $this->owner->Name;
    }

    /**
     * Returns the formated person's name
     * @return strnig
     */
    public function getPersonName() {
//        return $this->getFirstName();
        return $this->getAliasName();
    }

    /**
     * Returns the person's name and nickname
     * @return string
     */
    public function getAliasName() {
        $name = $this->getFirstName();

        if ($this->owner->NickName) {
            $name .= ' (' . $this->owner->NickName . ')';
        }

        return $name;
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function getFullName() {
        $name = $this->getPersonName();
        if (!$this->owner->Father()->exists()) {
            return $name;
        }

        return $name . ' ' . $this->owner->Father()->getFullName();
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function toIndexName() {
        $name = $this->owner->Name;
        if (!$this->Father()->exists()) {
            return $name;
        }

        return $name . ' ' . $this->Father()->toIndexName();
    }

    /**
     * Returns the person's age
     * @return string
     */
    public function getAge() {
        if ($this->owner->DeathDate && $this->owner->BirthDate) {
            return $this->owner->DeathDate - $this->owner->BirthDate;
        } else if ($this->owner->BirthDate) {
            $birth = new Date();
            $birth->setValue($this->owner->BirthDate);
            return $birth->TimeDiff();
        }

        return null;
    }

    /**
     * Returns the full name series of the person's parents.
     * @return string
     */
    public function getParents() {
        $person = $this->owner;
        $name = '';

        while ($person->Father()->exists()) {
            $person = $person->Father();
            $name .= ' ' . $person->getFirstName();
        }

        return $name;
    }

    /**
     * Returns the root of this person
     * @return Person
     */
    public function getRoot() {
        $person = $this->owner;

        while ($person->Father()->exists()) {
            $person = $person->Father();
        }

        return $person;
    }

    /**
     * Returns all sons and daughters
     * @return ArrayList
     */
    public function getChildren() {
        GenealogistHelper::get_children($this->owner);
    }

    public function ThumbPhoto() {
        return $this->owner->Photo()->CMSThumbnail();
    }

    /**
     * Checks if this person is older than 18 years
     * @return boolean
     */
    public function isAdult() {
        return $this->getAge() > 18;
    }

    /**
     * Checks if this person is male
     * @return boolean
     */
    public function isMale() {
        return $this->owner instanceof Male;
    }

    /**
     * Checks if this person is female
     * @return boolean
     */
    public function isFemale() {
        return $this->owner instanceof Female;
    }

    /**
     * Checks if this person is a clan
     * @return boolean
     */
    public function isClan() {
        return $this->owner instanceof Clan;
    }

}
