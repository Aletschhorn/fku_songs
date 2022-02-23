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
 * Reporting
 */
class Reporting extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * status
     *
     * @var int
     */
    protected $status = 0;
    
    /**
     * event 
     *
     * @var \FKU\FkuAgenda\Domain\Model\Event
     */
    protected $event = NULL;
    
    /**
     * song
     *
     * @var \FKU\FkuSongs\Domain\Model\Song
     */
    protected $song = NULL;
    
    /**
     * Returns the status
     *
     * @return int $status
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * Sets the status
     *
     * @param int $status
     * @return void
     */
    public function setStatus($status) {
        $this->status = $status;
    }
    
    /**
     * Returns the event
     *
     * @return \FKU\FkuAgenda\Domain\Model\Event $event
     */
    public function getEvent() {
        return $this->event;
    }
    
    /**
     * Sets the event
     *
     * @param \FKU\FkuAgenda\Domain\Model\Event $event
     * @return void
     */
    public function setEvent(\FKU\FkuAgenda\Domain\Model\Event $event) {
        $this->event = $event;
    }
    
    /**
     * Returns the song
     *
     * @return \FKU\FkuSongs\Domain\Model\Song $song
     */
    public function getSong()
    {
        return $this->song;
    }
    
    /**
     * Sets the song
     *
     * @param \FKU\FkuSongs\Domain\Model\Song $song
     * @return void
     */
    public function setSong(\FKU\FkuSongs\Domain\Model\Song $song)
    {
        $this->song = $song;
    }

}