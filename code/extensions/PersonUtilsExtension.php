<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonUtilsExtension
 *
 * @author hshatnawi
 */
class PersonUtilsExtension extends DataExtension {

    /// Counters ///
    /**
     * Counts the of all descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function DescendantsCount($state = 0) {
        if ($this->owner->Stats()->exists()) {
            return $this->MalesCount($state) + $this->FemalesCount($state);
        }
        return GenealogistHelper::count_descendants($this->owner, $state);
    }

    /**
     * Counts the of all male descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function MalesCount($state = 0) {
        if ($this->owner->Stats()->exists()) {
            return $state ? $this->owner->Stats()->LiveMales : $this->owner->Stats()->Males;
        }
        return GenealogistHelper::count_males($this->owner, $state);
    }

    /**
     * Counts the of all female descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function FemalesCount($state = 0) {
        if ($this->owner->Stats()->exists()) {
            return $state ? $this->owner->Stats()->LiveFemales : $this->owner->Stats()->Females;
        }
        return GenealogistHelper::count_females($this->owner, $state);
    }

    /**
     * Counts the of sons
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function SonsCount($state = 0) {
        if ($this->owner->Stats()->exists()) {
            return $state ? $this->owner->Stats()->LiveSons : $this->owner->Stats()->Sons;
        }
        return GenealogistHelper::count_sons($this->owner, $state);
    }

    /**
     * Counts the of daughters
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function DaughtersCount($state = 0) {
        if ($this->owner->Stats()->exists()) {
            return $state ? $this->owner->Stats()->LiveDaughters : $this->owner->Stats()->Daughters;
        }
        return GenealogistHelper::count_daughters($this->owner, $state);
    }

    /// Utils ///
    function reorderField($fields, $name, $fromTab, $toTab, $disabled = false) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
            $fields->addFieldToTab($toTab, $field);

            if ($disabled) {
                $field = $field->performDisabledTransformation();
            }
        }

        return $field;
    }

    function removeField($fields, $name, $fromTab) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
        }

        return $field;
    }

    function trim($field) {
        if ($this->$field) {
            $this->$field = trim($this->$field);
        }
    }

    public function toString() {
        return $this->getTitle();
    }

}
