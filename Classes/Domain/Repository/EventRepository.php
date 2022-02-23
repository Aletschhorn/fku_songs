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
 * The repository for Events
 */
class EventRepository extends \FKU\FkuAgenda\Domain\Repository\EventRepository {

	/**
	 * findLastEventsWithSongs
	 *
	 * @param string $categories Comma separated list of category UIDs
	 * @param integer $months Number of months back from today
	 * @param bool $descending Sorting in descending order instead of ascending
	 * @return
	 */
	public function findLastEventsWithSongs ($categories = '', $months = 3, $descending = false) {
		if (intval($months) <= 0) {
			$months = 3;
		}
		$dateLimit = new \DateTime('-'.$months.' months');

        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

		$constraints = array();
		$constraints[] = $query->greaterThanOrEqual('eventStart',$dateLimit);
		$constraints[] = $query->logicalNot($query->equals('songreporting',0));
		if ($categories != '') {
			$category = explode(',',$categories);
			// $constraints[] = $query->in('category.uid',$category);
			$constraints[] = $query->in('category',$category);
		}
		$query->matching($query->logicalAnd($constraints));
		if ($descending) {
			$query->setOrderings(array('eventStart' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		} else {
			$query->setOrderings(array('eventStart' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		}
		return $query->execute();
	}

}