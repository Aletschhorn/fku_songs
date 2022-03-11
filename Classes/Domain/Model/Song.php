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
 * Song
 */
class Song extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

    /**
     * title
     *
     * @var string
     */
    protected $title = '';
    
    /**
     * recommended
     *
     * @var boolean
     */
    protected $recommended = '';
    
    /**
     * language
     *
     * @var int
     */
    protected $language = 1;
    
    /**
     * author
     *
     * @var string
     */
    protected $author = '';
    
    /**
     * year
     *
     * @var int
     */
    protected $year = 0;
    
    /**
     * bpm
     *
     * @var int
     */
    protected $bpm = 0;
    
    /**
     * tone
     *
     * @var string
     */
    protected $tone = '';
    
    /**
     * ccliId
     *
     * @var int
     */
    protected $ccliId = 0;
    
    /**
     * copyright
     *
     * @var int
     */
    protected $copyright = 0;
    
    /**
     * ccliOffering
     *
     * @var int
     */
    protected $ccliOffering = 0;
    
    /**
     * links
     *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Link>
	 */
    protected $links = null;
    
    /**
     * youtube
     *
     * @var string
     */
    protected $youtube = '';
    
    /**
     * comment
     *
     * @var string
     */
    protected $comment = '';
    
    /**
     * relation
     *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song>
     */
    protected $relation = null;
    
    /**
     * relationFrom
     *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song>
     */
    protected $relationFrom = null;
    
	/**
	 * collection
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Collection>
	 */
    protected $collection = null;
    
	/**
	 * keywords
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Keyword>
	 */
    protected $keywords = null;
    
    /**
     * lastUsage
     *
     * @var \DateTime
     */
    protected $lastUsage = null;
    
    /**
     * songtext
     *
     * @var string
     */
    protected $songtext = '';
    
    /**
     * popularity
     *
     * @var float
     */
    protected $popularity = 0;
    
    /**
     * usages
     *
     * @var int
     */
    protected $usages = 0;
    
    /**
     * statisticUpdate
     *
     * @var \DateTime
     */
    protected $statisticUpdate = null;
    
    /**
     * sontextCopyright
     *
     * @var int
     */
    protected $sontextCopyright = 0;
	// This defines if it is OK to show the songtext in public events -- not stored in database but calculated
    
    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';
    
	/**
	 * __construct
	 *
	 * @return Event
	 */
	public function __construct() {

		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->links = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

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
     * Returns the recommended
     *
     * @return boolean $recommended
     */
    public function getRecommended() {
        return $this->recommended;
    }
    
    /**
     * Sets the recommended
     *
     * @param boolean $recommended
     * @return void
     */
    public function setRecommended($recommended) {
        $this->recommended = $recommended;
    }
    
	/**
	 * Returns the boolean state of recommended
	 *
	 * @return boolean
	 */
	public function isRecommended() {
		return $this->recommended;
	}

    /**
     * Returns the language
     *
     * @return int $language
     */
    public function getLanguage() {
        return $this->language;
    }
    
    /**
     * Sets the language
     *
     * @param int $language
     * @return void
     */
    public function setLanguage($language) {
        $this->language = $language;
    }
    
    /**
     * Returns the author
     *
     * @return string $author
     */
    public function getAuthor() {
        return $this->author;
    }
    
    /**
     * Sets the author
     *
     * @param string $author
     * @return void
     */
    public function setAuthor($author) {
        $this->author = $author;
    }
    
    /**
     * Returns the year
     *
     * @return int $year
     */
    public function getYear() {
        if ($this->year > 0) {
			return $this->year;
		} else {
			return NULL;
		}
    }
    
    /**
     * Sets the year
     *
     * @param int $year
     * @return void
     */
    public function setYear($year) {
        $this->year = $year;
    }
    
    /**
     * Returns the bpm
     *
     * @return int $bpm
     */
    public function getBpm() {
        if ($this->bpm > 0) {
			return $this->bpm;
		} else {
			return NULL;
		}
    }
    
    /**
     * Sets the bpm
     *
     * @param int $bpm
     * @return void
     */
    public function setBpm($bpm) {
        $this->bpm = $bpm;
    }
    
    /**
     * Returns the tone
     *
     * @return string $tone
     */
    public function getTone() {
        return $this->tone;
    }
    
    /**
     * Sets the tone
     *
     * @param string $tone
     * @return void
     */
    public function setTone($tone) {
        $this->tone = $tone;
    }
    
    /**
     * Returns the ccliId
     *
     * @return int $ccliId
     */
    public function getCcliId() {
        if ($this->ccliId > 0) {
			return $this->ccliId;
		} else {
			return NULL;
		}
    }
    
    /**
     * Sets the ccliId
     *
     * @param int $ccliId
     * @return void
     */
    public function setCcliId($ccliId) {
        $this->ccliId = $ccliId;
    }
    
    /**
     * Returns the ccliOffering
     *
     * @return int $ccliOffering
     */
    public function getCcliOffering() {
        return $this->ccliOffering;
    }
    
    /**
     * Sets the ccliOffering
     *
     * @param int $ccliOffering
     * @return void
     */
    public function setCcliOffering($ccliOffering) {
        $this->ccliOffering = $ccliOffering;
    }
    
    /**
     * Returns the copyright
     *
     * @return int $copyright
     */
    public function getCopyright() {
        return $this->copyright;
    }
    
    /**
     * Sets the copyright
     *
     * @param int $copyright
     * @return void
     */
    public function setCopyright($copyright) {
        $this->copyright = $copyright;
    }
    
	/**
	 * Adds a link
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Link $link
	 * @return void
	 */
	public function addLink(\FKU\FkuSongs\Domain\Model\Link $link) {
		$this->links->attach($link);
	}
	
	/**
	 * Removes a link
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Link $linkToRemove The link to be removed
	 * @return void
	 */
	public function removeLink(\FKU\FkuSongs\Domain\Model\Link $linkToRemove) {
		$this->links->detach($linkToRemove);
	}
	
	/**
	 * Returns the links
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Link> $links
	 */
	public function getLinks() {
		return $this->links;
	}
	
	/**
	 * Returns the links uids in an array
	 *
	 * @return \array $linkArray
	 */
	public function getLinksArray() {
		$linkArray = array ();
		if ($this->links) {
			foreach ($this->links as $link) {
				$linkArray[] = $link->getUid();
			}
		}
		return $linkArray;
	}
	/**
	 * Sets the links
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Link> $links
	 * @return void
	 */
	public function setLinks(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $links) {
		$this->links = $links;
	}

    /**
     * Returns the youtube
     *
     * @return string $youtube
     */
    public function getYoutube() {
        return $this->youtube;
    }
    
    /**
     * Sets the youtube
     *
     * @param string $youtube
     * @return void
     */
    public function setYoutube($youtube) {
        $this->youtube = $youtube;
    }
    
    /**
     * Returns the comment
     *
     * @return string $comment
     */
    public function getComment() {
        return $this->comment;
    }
    
    /**
     * Sets the comment
     *
     * @param string $comment
     * @return void
     */
    public function setComment($comment) {
        $this->comment = $comment;
    }
    
	/**
	 * Adds a relation
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Song $song
	 * @return void
	 */
	public function addRelation(\FKU\FkuSongs\Domain\Model\Song $song) {
		$this->collection->attach($song);
	}
	
	/**
	 * Removes a relation
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Song $relationToRemove The relation to be removed
	 * @return void
	 */
	public function removeRelation(\FKU\FkuSongs\Domain\Model\Song $relationToRemove) {
		$this->collection->detach($relationToRemove);
	}
	
	/**
	 * Returns the relation
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song> $relation
	 */
	public function getRelation() {
		return $this->relation;
	}
	
	/**
	 * Returns the relation uids in an array
	 *
	 * @return \array $relationArray
	 */
	public function getRelationArray() {
		$collectionArray = array ();
		foreach ($this->relation as $relation) {
			$relationArray[] = $relation->getUid();
		}
		return $relationArray;
	}
	
	/**
	 * Sets the relation
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song> $relation
	 * @return void
	 */
	public function setRelation(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relation) {
		$this->relation = $relation;
	}

	/**
	 * Returns the relationFrom
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song> $relationFrom
	 */
	public function getRelationFrom() {
		return $this->relationFrom;
	}
	
	/**
	 * Sets the relationFrom
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Song> $relationFrom
	 * @return void
	 */
	public function setRelationFrom(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relationFrom) {
		$this->relationFrom = $relationFrom;
	}

    /**
     * Return related from items sorted by title
     *
     * @return array
     */
    public function getAllRelated()
    {
        $all = [];
        $itemsRelated = $this->getRelation();
        if ($itemsRelated) {
            $all = array_merge($all, $itemsRelated->toArray());
        }

        $itemsRelatedFrom = $this->getRelationFrom();
        if ($itemsRelatedFrom) {
            $all = array_merge($all, $itemsRelatedFrom->toArray());
        }

        if (count($all) > 0) {
            usort($all, function($a, $b) {return strcmp($a->getTitle(), $b->getTitle());});
        }
        return $all;
    }

	/**
	 * Adds a collection
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Collection $collection
	 * @return void
	 */
	public function addCollection(\FKU\FkuSongs\Domain\Model\Collection $collection) {
		$this->collection->attach($collection);
	}
	
	/**
	 * Removes a collection
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Resource $collectionToRemove The collection to be removed
	 * @return void
	 */
	public function removeCollection(\FKU\FkuSongs\Domain\Model\Collection $collectionToRemove) {
		$this->collection->detach($collectionToRemove);
	}
	
	/**
	 * Returns the collection
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Collection> $collection
	 */
	public function getCollection() {
		return $this->collection;
	}
	
	/**
	 * Returns the collection uids in an array
	 *
	 * @return \array $CollectionArray
	 */
	public function getCollectionArray() {
		$collectionArray = array ();
		if ($this->collections) {
			foreach ($this->collections as $collection) {
				$collectionArray[] = $collection->getUid();
			}
		}
		return $collectionArray;
	}
	/**
	 * Sets the collection
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Collection> $collection
	 * @return void
	 */
	public function setCollection(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $collection) {
		$this->collection = $collection;
	}

	/**
	 * Adds a keyword
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Keyword $keyword
	 * @return void
	 */
	public function addKeyword(\FKU\FkuSongs\Domain\Model\Keyword $keyword) {
		if ($this->keywords === NULL) {
			$this->keywords = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		}
		$this->keywords->attach($keyword);
	}
	
	/**
	 * Removes a keyword
	 *
	 * @param \FKU\FkuSongs\Domain\Model\Keyword $keywordToRemove The keyword to be removed
	 * @return void
	 */
	public function removeKeyword(\FKU\FkuSongs\Domain\Model\Keyword $keywordToRemove) {
		$this->keywords->detach($keywordToRemove);
	}
	
	/**
	 * Returns the keywords
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Keyword> $keywords
	 */
	public function getKeywords() {
		return $this->keywords;
	}
	
	/**
	 * Returns the keyword uids in an array
	 *
	 * @return \array $keywordArray
	 */
	public function getKeywordArray() {
		$keywordArray = array ();
		if ($this->keywords) {
			foreach ($this->keywords as $keyword) {
				$keywordArray[] = $keyword->getUid();
			}
		}
		return $keywordArray;
	}
	/**
	 * Sets the keywords
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\FKU\FkuSongs\Domain\Model\Keyword> $keywords
	 * @return void
	 */
	public function setKeywords(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $keywords) {
		$this->keywords = $keywords;
	}

    /**
     * Returns the lastUsage
     *
     * @return \DateTime $lastUsage
     */
    public function getLastUsage() {
		return $this->lastUsage;
    }
    
    /**
     * Sets the lastUsage
     *
     * @param \DateTime $lastUsage
     * @return void
     */
    public function setLastUsage(\DateTime $lastUsage) {
        $this->lastUsage = $lastUsage;
		$timezone = new \DateTimeZone('Europe/Zurich');
        $this->lastUsage->setTimezone($timezone);
    }
    
    /**
     * Returns the songtext
     *
     * @return string $songtext
     */
    public function getSongtext() {
        return $this->songtext;
    }
    
    /**
     * Returns the songtext
     *
     * @return string $songtext
     */
    public function getSongtextFormatted() {
		$patterns = [
			'/^Intro.*$/mi',
			'/^Vers.*$/mi',
			'/^Strophe.*$/mi',
			'/^Chorus.*$/mi',
			'/^Refrain.*$/mi',
			'/^Pre-Chorus.*$/mi',			
			'/^Pre-Refrain.*$/mi',			
			'/^Bridge.*$/mi',
			'/^Ending.*$/mi',
			'/^Teil.*$/mi'
		];

//		$patterns = '/^Vers.*$/mi';
		$songtext = nl2br($this->songtext);
		$songtext = preg_replace($patterns,'<b>$0</b>',$songtext);
        return $songtext;
    }
    
    /**
     * Sets the songtext
     *
     * @param string $songtext
     * @return void
     */
    public function setSongtext($songtext) {
        $this->songtext = $songtext;
    }
    
    /**
     * Returns the popularity
     *
     * @return float $popularity
     */
    public function getPopularity() {
        return $this->popularity;
    }
    
    /**
     * Sets the popularity
     *
     * @param float $popularity
     * @return void
     */
    public function setPopularity($popularity) {
        $this->popularity = $popularity;
    }
    
    /**
     * Returns the usages
     *
     * @return int $usages
     */
    public function getUsages() {
        return $this->usages;
    }
    
    /**
     * Sets the usages
     *
     * @param float $usages
     * @return void
     */
    public function setUsages($usages) {
        $this->usages = $usages;
    }
    
    /**
     * Returns the statisticUpdate
     *
     * @return \DateTime $statisticUpdate
     */
    public function getStatisticUpdate() {
		return $this->statisticUpdate;
    }
    
    /**
     * Sets the statisticUpdate
     *
     * @param \DateTime $statisticUpdate
     * @return void
     */
    public function setStatisticUpdate(\DateTime $statisticUpdate) {
        $this->statisticUpdate = $statisticUpdate;
		$timezone = new \DateTimeZone('Europe/Zurich');
        $this->statisticUpdate->setTimezone($timezone);
    }
    
    /**
     * Returns the songtextCopyright
     *
     * @return int $songtextCopyright
     */
    public function getSongtextCopyright() {
		if (in_array($this->copyright, [1,2,3]) or in_array($this->ccliOffering, [2,3,4])) {
			$this->songtextCopyright = 1;
		} elseif ($this->ccliOffering == 1) {
			$this->songtextCopyright = -1;
		} else {
			$this->songtextCopyright = 0;
		}
		return $this->songtextCopyright;
    }
	
    /**
     * Returns the slug
     *
     * @return string $slug
     */
    public function getSlug() {
        return $this->slug;
    }
    
    /**
     * Sets the slug
     *
     * @param string $slug
     * @return void
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }
    

}