<?php
namespace FKU\FkuSongs\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Daniel Widmer <daniel.widmer@fku.ch>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Source
 */
class Source extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = '';
    
    /**
     * acronym
     *
     * @var string
     */
    protected $acronym = '';
    
    /**
     * acronym
     *
     * @var boolean
     */
    protected $withrecommended = false;
    
    /**
     * Returns the title
     *
     * @return string $title
     */
    public function getTitle() {
        return $this->title;
    }
    
    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Returns the acronym
     *
     * @return string $acronym
     */
    public function getAcronym() {
        return $this->acronym;
    }
    
    /**
     * Sets the acronym
     *
     * @param string $acronym
     * @return void
     */
    public function setAcronym($acronym) {
        $this->acronym = $acronym;
    }

    /**
     * Returns the withrecommended
     *
     * @return boolean $withrecommended
     */
    public function getWithrecommended() {
        return $this->withrecommended;
    }
    
    /**
     * Checks the withrecommended
     *
     * @return boolean $withrecommended
     */
    public function isWithrecommended() {
        return $this->withrecommended;
    }
    
    /**
     * Sets the withrecommended
     *
     * @param boolean $withrecommended
     * @return void
     */
    public function setWithrecommended($withrecommended) {
        $this->withrecommended = $withrecommended;
    }

}