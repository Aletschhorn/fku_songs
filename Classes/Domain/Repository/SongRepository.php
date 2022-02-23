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
 * The repository for Songs
 */
class SongRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
	
    /**
     * allSources
     *
     * @var object
     */
	protected $allSources;

	/**
	* defaultOrderings
	*
	* @var array
	*/
	protected $defaultOrderings = array('title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING);

	/**
	* findByUidAssoc: fetch assoc array instead of the mapped object 
	*
	* @param \int $uid
	* @return
	*/
	public function findByUidAssoc($uid) {
		$query = $this->createQuery();
		$query->matching($query->equals('uid', $uid));
		return $query->execute(true)[0];
	}
	
	/**
	* findAll
	*
	* @return
	*/
	public function findAll() {
		$query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);
		return $query->execute();
	}	

	/**
	* findFiltered
	*
	* @param \string $searchword
	* @param \string $source
	* @param \string $language
	* @param \bool $recommended Only finds media that are flagged as "recommended"
	* @param \bool $lyrics Also searches in songtext
	* @param \bool $rejected Include rejected songs (useless if not source is given)
	* @param \string $sorting
	* @param \int $limit
	* @param \int $offset
	* @return
	*/
	public function findFiltered($searchword = NULL, $source = '', $language = '', $recommended = 0, $lyrics = 0, $rejected = 1, $sorting = 'title', $limit = 20, $offset = 0) {
		$query = $this->createFilteredQuery($searchword, $source, $language, $recommended, $lyrics, $rejected);
		if ($sorting == 'source') {
			$query->setOrderings(array('collection.source.sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING, 'collection.number' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING));
		} elseif ($sorting == 'popularity') {
			$query->setOrderings(array('popularity' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING));
		}
		$query->setLimit($limit)->setOffset($offset);
		return $query->execute();
	}	

	/**
	* countFiltered
	*
	* @param \string $searchword
	* @param \string $source
	* @param \string $language
	* @param \bool $recommended Only finds media that are flagged as "recommended"
	* @param \bool $lyrics Also searches in songtext
	* @param \bool $rejected Include rejected songs (useless if not source is given)
	* @return
	*/
	public function countFiltered($searchword = NULL, $source = '', $language = '', $recommended = 0, $lyrics = 0, $rejected = 1) {
		$query = $this->createFilteredQuery($searchword, $source, $language, $recommended, $lyrics, $rejected);
		return $query->count();
	}	

	/**
	* findWithKeyword
	*
	* @return
	*/
	public function findWithKeyword() {
		$query = $this->createQuery();
		$query->matching($query->greaterThan('keywords',0));
		return $query->execute();
	}

	/**
	* findBySingleKeyword
	*
	* @param \int $keyword ID of keyword in table
	* @param \int $limit
	* @param \int $offset
	* @return
	*/
	public function findBySingleKeyword($keyword = 0, $limit = 20, $offset = 0) {
		
		if ($keyword > 0) {
			$query = $this->createQuery();
			$query->matching($query->like('keywords.uid',$keyword));
			$query->setLimit($limit)->setOffset($offset);
			return $query->execute();
		} else {
			return false;
		}
	}

	/**
	* countBySingleKeyword
	*
	* @param \int $keyword ID of keyword in table
	* @param \int $limit
	* @param \int $offset
	* @return
	*/
	public function countBySingleKeyword($keyword = 0) {
		if ($keyword > 0) {
			$query = $this->createQuery();
			$query->matching($query->like('keywords.uid',$keyword));
			return $query->execute()->count();
		} else {
			return 0;
		}
	}

	/**
	* countPerKeyword
	*
	* @return
	*/
	public function countPerKeyword() {
		$songs = $this->findWithKeyword();
		$list = [];
		foreach ($songs as $song) {
			foreach ($song->getKeywordArray() as $keywordId) {
				$list[$keywordId][] = $song;
			}
		}
		return $list;
	}

	/**
	* createFilteredQuery
	*
	* @param \string $searchword
	* @param \string $sources
	* @param \string $language
	* @param \bool $recommended Only finds media that are flagged as "recommended"
	* @param \bool $lyrics Also searches in songtext
	* @param \bool $rejected Include rejected songs (useless if not source is given)
	* @return
	*/
	private function createFilteredQuery($searchword = NULL, $sources = '', $language = '', $recommended = 0, $lyrics = 0, $rejected = 1) {

		$sourceWithRecommended = [];
		foreach ($this->allSources as $singleSource) {
			$sourceWithRecommended[$singleSource->getUid()] = $singleSource->getWithrecommended();
		}
		
		$query = $this->createQuery();
		$constraints = [];

		if ($searchword != '') {
			$swordContraints = [
				$query->like('title','%'.$searchword.'%'),
				$query->like('author','%'.$searchword.'%'),
				$query->like('collection.number',$searchword),
				$query->like('keywords.title',$searchword)
			];
			if ($lyrics == 1) {
				$swordContraints[] = $query->like('songtext','%'.$searchword.'%');
			}
			$constraints[] = $query->logicalOr($swordContraints);
		}
		
		$sourceConstraints = [];
		$sourceArray = explode(',',$sources);
		foreach ($sourceArray as $source) {
			if ($source == 0) {
				$sourceConstraints[] = $query->equals('collection.source',NULL);
			} else {
				$subContraints = [];
				$subContraints[] = $query->equals('collection.source.uid',$source);
				if ($rejected == false) {
					$subContraints[] = $query->equals('collection.rejected',0);
				}
				if ($recommended and $sourceWithRecommended[$source]) {
					$subContraints[] = $query->equals('recommended',1);
				}
				if (count($subContraints) > 1) {
					$sourceConstraints[] = $query->logicalAnd($subContraints);
				} else {
					$sourceConstraints[] = $subContraints[0];
				}
			}
		}
		if (count($sourceConstraints) > 1) {
			$constraints[] = $query->logicalOr($sourceConstraints);
		} elseif (count($sourceConstraints) == 1) {
			$constraints[] = $sourceConstraints[0];
		}
/*		
		if (count($source) > 0) {
			if (in_array(0,$source)) {
				$constraints[] = $query->logicalOr(array($query->in('collection.source.uid',$source),$query->equals('collection.source',NULL)));
			} else {
				if ($rejected) {
					$constraints[] = $query->in('collection.source.uid',$source);
				} else {
					$constraints[] = $query->logicalAnd($query->in('collection.source.uid',$source),$query->equals('collection.rejected',0));
				}
			}
		}

		if ($recommended == 1) {
			$constraints[] = $query->equals('recommended',1);
		}
*/
		$lang = explode(',',$language);
		$constraints[] = $query->in('language',$lang);

		$query->matching($query->logicalAnd($constraints));
		return $query;
	}

	/**
	* setAllSources
	*
	* @param \object $sources
	* @return
	*/
	public function setAllSources($sources) {
		$this->allSources = $sources;
	}

}