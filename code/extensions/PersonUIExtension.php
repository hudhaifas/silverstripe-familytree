<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PersonUIExtension
 *
 * @author hshatnawi
 */
class PersonUIExtension extends DataExtension {

    public function CSSClasses($stopAtClass = 'DataObject') {
        $classes = strtolower(parent::CSSClasses($stopAtClass));

        $classes .= $this->owner->IsDead ? ' dead' : '';

        return $classes;
    }

}
