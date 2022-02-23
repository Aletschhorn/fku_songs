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
 * Collection
 */
class Collection extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * number
     *
     * @var int
     */
    protected $number = 0;
    
    /**
     * source
     *
     * @var \FKU\FkuSongs\Domain\Model\Source
     */
    protected $source = null;
    
    /**
     * rejected
     *
     * @var boolean
     */
    protected $rejected = false;
    
    /**
     * slide
     *
     * @var int
     */
    protected $slide = 0;
    
    /**
     * Returns the number
     *
     * @return int $number
     */
    public function getNumber()
    {
        if ($this->number > 0) {
			return $this->number;
		} else {
			return NULL;
		}
    }
    
    /**
     * Sets the number
     *
     * @param int $number
     * @return void
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }
    
    /**
     * Returns the source
     *
     * @return \FKU\FkuSongs\Domain\Model\Source $source
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Sets the source
     *
     * @param \FKU\FkuSongs\Domain\Model\Source $source
     * @return void
     */
    public function setSource(\FKU\FkuSongs\Domain\Model\Source $source)
    {
        $this->source = $source;
    }

    /**
     * Returns the rejected
     *
     * @return boolean $rejected
     */
    public function getRejected()
    {
        return $this->rejected;
    }
    
    /**
     * Sets the rejected
     *
     * @param boolean $rejected
     * @return void
     */
    public function setRejected($rejected)
    {
        $this->rejected = $rejected;
    }
    
	/**
	 * Returns the boolean state of rejected
	 *
	 * @return boolean
	 */
	public function isRejected() {
		return $this->rejected;
	}

    /**
     * Returns the slide
     *
     * @return int $slide
     */
    public function getSlide() {
        if ($this->slide > 0) {
			return $this->slide;
		} else {
			return NULL;
		}
    }
    
    /**
     * Sets the slide
     *
     * @param int $slide
     * @return void
     */
    public function setSlide($slide) {
        $this->slide = $slide;
    }
    
}