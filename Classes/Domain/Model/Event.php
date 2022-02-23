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
 * Event
 */
class Event extends \FKU\FkuAgenda\Domain\Model\Event {

	/**
	 * songreporting
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Reporting>
	 */
    protected $songreporting = null;
    
	/**
	 * Adds a songreporting
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Reporting $reporting
	 * @return void
	 */
	public function addSongreporting(\FKU\FkuSongs\Domain\Model\Reporting $reporting) {
		$this->songreporting->attach($reporting);
	}
	
	/**
	 * Removes a songreporting
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Reporting $reporting The reporting to be removed
	 * @return void
	 */
	public function removeSongreporting(\FKU\FkuSongs\Domain\Model\Reporting $reporting) {
		$this->songreporting->detach($reporting);
	}
	
	/**
	 * Returns the songreporting
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Reporting> $songreporting
	 */
	public function getSongreporting() {
		return $this->songreporting;
	}

	/**
	 * Sets the songreporting
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Reporting> $songreporting
	 * @return void
	 */
	public function setSongreporting(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $songreporting) {
		$this->songreporting = $songreporting;
	}

}