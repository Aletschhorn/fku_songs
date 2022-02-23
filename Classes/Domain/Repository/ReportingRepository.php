<?php
namespace FKU\FkuSongs\Domain\Repository;

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
 * The repository for Reportings
 */
class ReportingRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
	public function findAll () {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
        return $query->execute();
	}
	
	
	/**
	 * findLast
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Song $song
	 * @param integer $months
	 * @return
	 */
	public function findLast ($song, $months = 3) {
		if (intval($months) <= 0) {
			$months = 3;
		}
		$dateLimit = new \DateTime('-'.$months.' months');
		
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
		$query->matching(
			$query->logicalAnd(
				$query->equals('song',$song),
				$query->greaterThanOrEqual('event.eventStart',$dateLimit)
			)
		)->setOrderings(array('event.eventStart' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		return $query->execute();
	}
	
	/**
	 * findByUsage
	 *
	 * @param string $categories Comma separated list of category UIDs
	 * @param integer $months Number of months back from today
	 * @param integer $threshold Minimum number of usage to be taken
	 * @return
	 */
	public function findByUsage ($categories = '', $months = 3, $threshold = 2) {
		
		if (intval($months) <= 0) {
			$months = 3;
		}
		$dateLimit = new \DateTime('-'.$months.' months');
		
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

		$constraints = array($query->greaterThanOrEqual('event.eventStart',$dateLimit));
		if ($categories != '') {
			$category = explode(',',$categories);
			$constraints[] = $query->in('event.category.uid',$category);
		}
		$query->matching($query->logicalAnd($constraints));
		$reportings = $query->execute();
		
		$res = array();
		foreach ($reportings as $report) {
			$res[$report->getSong()->getUid()]['song'] = $report->getSong();
			$res[$report->getSong()->getUid()]['count']++;
		}
		foreach ($res as $item) {
			if ($item['count'] >= $threshold) {
				$result[$item['count']][] = $item['song'];
			}
		}
		if ($result) {
			krsort($result);
		}
		return $result;
	}
	
	/**
	 * findByEvent
	 *
	 * @param \FKU\FkuAgenda\Domain\Model\Event $event
	 * @return
	 */
	public function findByEvent ($event) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
		$query->matching($query->equals('event.uid',$event->getUid()));
		return $query->execute();
	}

	/**
	 * findLastEvents
	 *
	 * @param string $categories Comma separated list of category UIDs
	 * @param integer $months Number of months back from today
	 * @return
	 */
	public function findLastEvents ($categories = '', $months = 3) {
		if (intval($months) <= 0) {
			$months = 3;
		}
		$dateLimit = new \DateTime('-'.$months.' months');

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
		$query->matching($query->greaterThanOrEqual('event.eventStart',$dateLimit));
		if ($categories != '') {
			$category = explode(',',$categories);
			$constraints[] = $query->in('event.category.uid',$category);
		}
		$result = $query->execute();
		
		$events = array();
		foreach ($result as $key => $value) {
			if (! in_array($value->getEvent(),$events)) {
				$events[] = $value->getEvent();
			}
		}
		return $events;
	}

	/**
	 * findByStatus
	 *
	 * @param \integer $status
	 * @return
	 */
	public function findByStatus ($status) {
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
		$query->matching($query->equals('status',$status));
		return $query->execute();
	}

}