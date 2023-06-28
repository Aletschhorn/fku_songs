<?php
namespace FKU\FkuSongs\Controller;

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
 * SongController
 */

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use FKU\FkuSongs\Domain\Repository\SongRepository;
use FKU\FkuSongs\Domain\Repository\SourceRepository;
use FKU\FkuSongs\Domain\Repository\ReportingRepository;
use FKU\FkuSongs\Domain\Repository\KeywordRepository;
use FKU\FkuSongs\Domain\Repository\LinkRepository;

class SongController extends ActionController {

    /**
     * link types
     */
    protected $linkTypes = array(0 => 'Liedtext', 1 => 'Akkorde', 2 => 'Noten', 3 => 'Präsentation');

    /**
     * serach modes (predefined options)
     */
    protected $searchModes = [1 => 'Für Gottesdienst', 2 => 'Für Highwäg', 9 => 'Benutzerdefiniert'];
	
    /**
     * Pre-defined search filter parameters
     */
	protected $filterDefault = [
		'source' => '0,1,2,3,4,5', 
		'language' => '1,2,3,9',
		'recommended' => 0,
		'lyrics' => 0,
		'sorting' => 'title',
		'showTone' => 1,
		'showLastUsage' => 0,
		'showPopularity' => 0,
		'showCopyright' => 1
	];
	
    /**
     * Pre-defined search filter parameters for Gottesdienst
     */
	protected $filterGottesdienst = [
		'source' => '1,2,3', 
		'language' => '1,2,3,9',
		'recommended' => 1,
		'lyrics' => 0,
		'sorting' => 'title',
		'showTone' => 1,
		'showLastUsage' => 0,
		'showPopularity' => 0,
		'showCopyright' => 1
	];
	
    /**
     * Pre-defined search filter parameters for Gottesdienst
     */
	protected $filterHighwaeg = [
		'source' => '4', 
		'language' => '1,2,3,9',
		'recommended' => 0,
		'lyrics' => 0,
		'sorting' => 'title',
		'showTone' => 1,
		'showLastUsage' => 0,
		'showPopularity' => 0,
		'showCopyright' => 1
	];
	
    /**
     * Variable names stored in cookies
     */
	protected $cookieVariables = ['searchword', 'predefined', 'source', 'language', 'recommended', 'lyrics', 'sorting', 'showTone', 'showLastUsage', 'showPopularity', 'showCopyright'];


    /**
     * songRepository
     *
     * @var SongRepository
     */
    protected $songRepository;
    
	/**
	 * sourceRepository
	 *
	 * @var SourceRepository
	 */
	protected $sourceRepository;

	/**
	 * reportingRepository
	 *
	 * @var ReportingRepository
	 */
	protected $reportingRepository;

	/**
	 * keywordRepository
	 *
	 * @var KeywordRepository
	 */
	protected $keywordRepository;

	/**
	 * linkRepository
	 *
	 * @var LinkRepository
	 */
	protected $linkRepository;

	/**
	 * @param SongRepository $songRepository
	 * @param SourceRepository $sourceRepository
	 * @param ReportingRepository $reportingRepository
	 * @param KeywordRepository $keywordRepository
	 * @param LinkRepository $linkRepository
	 */
	public function __construct(
			SongRepository $songRepository,
			SourceRepository $sourceRepository,
			ReportingRepository $reportingRepository,
			KeywordRepository $keywordRepository,
			LinkRepository $linkRepository
		) {
		$this->songRepository = $songRepository;
		$this->sourceRepository = $sourceRepository;
		$this->reportingRepository = $reportingRepository;
		$this->keywordRepository = $keywordRepository;
		$this->linkRepository = $linkRepository;
	}


    /**
     * action createSlugs
	 * Used once for creation of existing songs' slugs
     *
     * @return void
     */
    public function createSlugsAction() {
		$songs = $this->songRepository->findAll();
		$count = count($songs);
		$tableName = 'tx_fkusongs_domain_model_song';
		$slugFieldName = 'slug';
		$fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config'];
		$slugHelper = GeneralUtility::makeInstance(SlugHelper::class, $tableName, $slugFieldName, $fieldConfig);
		$persistenceManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class);
		foreach ($songs as $song) {
			if ($song->getSlug() == '') {
				$record = $this->songRepository->findByUidAssoc($song->getUid());
				$slug = $slugHelper->generate($record, $record['pid']);
				$state = RecordStateFactory::forName($tableName)->fromArray($record, $record['pid'], $record['uid']);
				$slug = $slugHelper->buildSlugForUniqueInTable($slug, $state);
				$song->setSlug($slug);
				$this->songRepository->update($song);
				$persistenceManager->persistAll();
				$countModified++;
				$list[] = [$song->getUid(), $song->getTitle(), $slug];
			}
		}
		$this->view->assignMultiple(array(
			'count' => $count,
			'list' => $list,
		));
	}
	
    /**
     * action transformLinks
	 * Used once for update links of related documents
     *
     * @return void
     */
    public function transformLinksAction() {
		$songs = $this->songRepository->findAll();
		$countTotal = 0;
		$countLyrics = 0;
		$countChords = 0;
		$countLeadsheet = 0;
		$countPresentation = 0;
		foreach ($songs as $song) {
			$countTotal++;
			$link = $song->getLinkLyrics();
			if ($link) {
				$linkOrig = $link;
				if (stristr($link, '?goto=')) {
					$link = 'https://www.fku.ch/dateien/ws-' . substr($link, strpos($link, '=')+1);
				}
				if (stristr($link, '/ws-gottesdienst-1')) {
					$link = str_replace('/ws-gottesdienst-1', '/data/gottesdienst', $link);
				} elseif (stristr($link, '/ws-highwaeg-1')) {
					$link = str_replace('/ws-highwaeg-1', '/data/highwaeg', $link);
				}
				if ($linkOrig != $link) {
					$song->setLinkLyrics($link);
					$countLyrics++;
				}
			}
			$link = $song->getLinkChords();
			if ($link) {
				$linkOrig = $link;
				if (stristr($link, '?goto=')) {
					$link = 'https://www.fku.ch/dateien/ws-' . substr($link, strpos($link, '=')+1);
				}
				if (stristr($link, '/ws-gottesdienst-1')) {
					$link = str_replace('/ws-gottesdienst-1', '/data/gottesdienst', $link);
				} elseif (stristr($link, '/ws-highwaeg-1')) {
					$link = str_replace('/ws-highwaeg-1', '/data/highwaeg', $link);
				}
				if ($linkOrig != $link) {
					$song->setLinkChords($link);
					$countChords++;
				}
			}
			$link = $song->getLinkLeadsheet();
			if ($link) {
				$linkOrig = $link;
				if (stristr($link, '?goto=')) {
					$link = 'https://www.fku.ch/dateien/ws-' . substr($link, strpos($link, '=')+1);
				}
				if (stristr($link, '/ws-gottesdienst-1')) {
					$link = str_replace('/ws-gottesdienst-1', '/data/gottesdienst', $link);
				} elseif (stristr($link, '/ws-highwaeg-1')) {
					$link = str_replace('/ws-highwaeg-1', '/data/highwaeg', $link);
				}
				if ($linkOrig != $link) {
					$song->setLinkLeadsheet($link);
					$countLeadsheet++;
				}
			}
			$link = $song->getLinkPresentation();
			if ($link) {
				$linkOrig = $link;
				if (stristr($link, '?goto=')) {
					$link = 'https://www.fku.ch/dateien/ws-' . substr($link, strpos($link, '=')+1);
				}
				if (stristr($link, '/ws-gottesdienst-1')) {
					$link = str_replace('/ws-gottesdienst-1', '/data/gottesdienst', $link);
				} elseif (stristr($link, '/ws-highwaeg-1')) {
					$link = str_replace('/ws-highwaeg-1', '/data/highwaeg', $link);
				}
				if ($linkOrig != $link) {
					$song->setLinkPresentation($link);
					$countPresentation++;
				}
			}
			$this->songRepository->update($song);
		}
		$this->view->assignMultiple(array(
			'countTotal' => $countTotal,
			'countLyrics' => $countLyrics,
			'countChords' => $countChords,
			'countLeadsheet' => $countLeadsheet,
			'countPresentation' => $countPresentation,
		));

	}
	
    /**
     * action list
     *
     * @return void
     */
    public function listAction() {
		
		$sources = $this->sourceRepository->findAll();
		$allSources = $this->sourceRepository->findAllAsList();

		$arguments = $this->request->getArguments();
		
		if ($arguments['reset'] == 1 or intval($GLOBALS['TSFE']->fe_user->getKey('ses','songPage')) == 0) {
			// reset filter values to default
			$filter = $this->filterDefault;
			$filter['predefined'] = 1;
			$filter['searchword'] = '';
			$pagenow = 1;
			
			// set cookies to default values
			foreach ($this->cookieVariables as $cookieVar) {
				$GLOBALS['TSFE']->fe_user->setKey('ses','songFilter'.ucfirst($cookieVar),$filter[$cookieVar]);
			}
		} else {
			// get cookies
			foreach ($this->cookieVariables as $cookieVar) {
				$filter[$cookieVar] = $GLOBALS['TSFE']->fe_user->getKey('ses','songFilter'.ucfirst($cookieVar));
			}
			if ($arguments['page'] > 0) { 
				$pagenow = intval($arguments['page']); 
			} else {
				$pagenow = intval($GLOBALS['TSFE']->fe_user->getKey('ses','songPage'));
			}
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songPage',$pagenow);
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','songList');

		// Get values from search form (and pagination)
		if (is_array($arguments['filter'])) { 
			$tempFilter = $arguments['filter'];
			$tempFilterGottesdienst = $arguments['set1'];
			$tempFilterHighwaeg = $arguments['set2'];

			$search = [', ',',','  '];
			$replace = [' ',' ',' '];
			$filter['searchword'] = str_replace($search, $replace, $tempFilter['searchword']);
			$filter['predefined'] = intval($tempFilter['predefined']);
			
			switch ($filter['predefined']) {
				case 1:
					$filter['showTone'] = $tempFilterGottesdienst['showTone'];
					$filter['showLastUsage'] = $tempFilterGottesdienst['showLastUsage'];
					$filter['showPopularity'] = $tempFilterGottesdienst['showPopularity'];
					$filter['showCopyright'] = $tempFilterGottesdienst['showCopyright'];
					break;
				case 2:
					$filter['showTone'] = $tempFilterHighwaeg['showTone'];
					$filter['showLastUsage'] = $tempFilterHighwaeg['showLastUsage'];
					$filter['showPopularity'] = $tempFilterHighwaeg['showPopularity'];
					$filter['showCopyright'] = $tempFilterHighwaeg['showCopyright'];
					break;
				case 9:
					if (is_array($tempFilter['source'])) {
						$filter['source'] = implode(',',array_keys($tempFilter['source'],1));
					} else {
						$filter['source'] = $tempFilter['source'];
					}
					if (is_array($tempFilter['language'])) {
						$filter['language'] = implode(',',array_keys($tempFilter['language'],1));
					} else {
						$filter['language'] = $tempFilter['language'];
					}
					$filter['recommended'] = $tempFilter['recommended'];
					$filter['lyrics'] = $tempFilter['lyrics'];
					$filter['sorting'] = $tempFilter['sorting'];
					$filter['showTone'] = $tempFilter['showTone'];
					$filter['showLastUsage'] = $tempFilter['showLastUsage'];
					$filter['showPopularity'] = $tempFilter['showPopularity'];
					$filter['showCopyright'] = $tempFilter['showCopyright'];
					break;
			}
			
		
			// overwrite cookies with new data
			foreach ($this->cookieVariables as $cookieVar) {
				$GLOBALS['TSFE']->fe_user->setKey('ses','songFilter'.ucfirst($cookieVar),$filter[$cookieVar]);
			}
		}
		
		if (! $filter['predefined']) {
			$filter['predefined'] = 1;
		}

		$songs = [];
		$total = 0;
		$limit = intval($this->settings['resultsPerPage']);
		$pagetotal = 1;
		$offset = ($pagenow - 1) * $limit;

		// Get songs from repository
		$this->songRepository->setAllSources($this->sourceRepository->findAll());
		switch ($filter['predefined']) {
			case 1:
				$songs = $this->songRepository->findFiltered(
					$filter['searchword'], 
					$this->filterGottesdienst['source'], 
					$this->filterGottesdienst['language'], 
					$this->filterGottesdienst['recommended'], 
					$this->filterGottesdienst['lyrics'], 
					true, 
					$this->filterGottesdienst['sorting'], 
					$limit, 
					$offset
				);
				$total = $this->songRepository->countFiltered(
					$filter['searchword'], 
					$this->filterGottesdienst['source'], 
					$this->filterGottesdienst['language'], 
					$this->filterGottesdienst['recommended'], 
					$this->filterGottesdienst['lyrics'], 
					true
				);
				break;
			case 2:
				$songs = $this->songRepository->findFiltered(
					$filter['searchword'], 
					$this->filterHighwaeg['source'], 
					$this->filterHighwaeg['language'], 
					$this->filterHighwaeg['recommended'], 
					$this->filterHighwaeg['lyrics'], 
					true, 
					$this->filterHighwaeg['sorting'], 
					$limit, 
					$offset
				);
				$total = $this->songRepository->countFiltered(
					$filter['searchword'], 
					$this->filterHighwaeg['source'], 
					$this->filterHighwaeg['language'], 
					$this->filterHighwaeg['recommended'], 
					$this->filterHighwaeg['lyrics'], 
					true
				);
				break;
			case 9:
				if ($filter['source'] != '' and $filter['language'] != '') {
					$songs = $this->songRepository->findFiltered(
						$filter['searchword'], 
						$filter['source'], 
						$filter['language'], 
						$filter['recommended'], 
						$filter['lyrics'], 
						true, 
						$filter['sorting'], 
						$limit, 
						$offset
					);
					$total = $this->songRepository->countFiltered(
						$filter['searchword'], 
						$filter['source'], 
						$filter['language'], 
						$filter['recommended'], 
						$filter['lyrics'], 
						true
					);
				}
				break;
		}
		
		$filterGottesdienst = $this->filterGottesdienst;
		$filterGottesdienst['showTone'] = $filter['showTone'];
		$filterGottesdienst['showLastUsage'] = $filter['showLastUsage'];
		$filterGottesdienst['showPopularity'] = $filter['showPopularity'];
		$filterGottesdienst['showCopyright'] = $filter['showCopyright'];
		
		$filterHighwaeg = $this->filterHighwaeg;
		$filterHighwaeg['showTone'] = $filter['showTone'];
		$filterHighwaeg['showLastUsage'] = $filter['showLastUsage'];
		$filterHighwaeg['showPopularity'] = $filter['showPopularity'];
		$filterHighwaeg['showCopyright'] = $filter['showCopyright'];

		if ($total > 0) {
			// Calculate pages numbers and values for pagination
			if ($total > $limit) {
				$pagetotal = ceil($total / $limit);
			}
			$page = array ('now' => $pagenow, 'prev' => max(1,$pagenow-1), 'next' => min($pagetotal,$pagenow+1), 'total' => $pagetotal);
			$pagearray = array();
			if ($pagenow > 7) {
				$pagearray = array (1, 2, 3, '...');
				for ($i=$pagenow-3; $i<=$pagenow; $i++) {
					$pagearray[] = $i;
				}
			} else {
				for ($i=1;$i<=$pagenow;$i++) {
					$pagearray[] = $i;
				}
			}
			if ($pagetotal - $pagenow > 6) {
				$pagearray[] = $pagenow+1;
				$pagearray[] = $pagenow+2;
				$pagearray[] = $pagenow+3;
				$pagearray[] = '...';
				for ($i=$pagetotal-1; $i<=$pagetotal; $i++) {
					$pagearray[] = $i;
				}
			} elseif ($pagenow < $pagetotal) {
				for ($i=$pagenow+1; $i<=$pagetotal; $i++) {
					$pagearray[] = $i;
				}
			}
		} else {
			$page = 1;
			$pagearray = array();
		}

		$this->view->assignMultiple(array(
			'songs' => $songs,
			'total' => $total,
			'page' => $page,
			'pagearray' => $pagearray,
			'sources' => $sources,
			'filter' => $filter,
			'filterSet1' => $filterGottesdienst,
			'filterSet2' => $filterHighwaeg,
			'predefOptions' => $this->searchModes,
			'settings' => $this->settings,
		));
    }
    
    /**
     * action keywordAction
     *
     * @return void
     */
    public function keywordAction() {
		
		$sources = $this->sourceRepository->findAll();
		$allSources = $this->sourceRepository->findAllAsList();
		$arguments = $this->request->getArguments();
		$keywords = $this->keywordRepository->findWithSongs();
		
		// Initialize
		if ($GLOBALS['TSFE']->fe_user->getKey('ses','songKeyword') == 0) {
			$filter = $this->filterDefault;
			$keyword = 0;
			$pagenow = 1;
		} else {
			// get cookies
			foreach ($this->cookieVariables as $cookieVar) {
				$filter[$cookieVar] = $GLOBALS['TSFE']->fe_user->getKey('ses','songFilter'.ucfirst($cookieVar));
			}
			$keyword = intval($GLOBALS['TSFE']->fe_user->getKey('ses','songKeyword'));
			if ($arguments['page'] > 0) { 
				$pagenow = intval($arguments['page']); 
			} else {
				$pagenow = intval($GLOBALS['TSFE']->fe_user->getKey('ses','songKeywordPage'));
			}
		}

		if (! $filter['predefined']) {
			$filter['predefined'] = 1;
		}

		// Get values from search form
		if ($arguments['keyword']) {
			$keyword = intval($arguments['keyword']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songKeyword',$keyword);
			$pagenow = 1;
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songKeywordPage',$pagenow);
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','songKeyword');
		
		if ($keyword > 0) {
			$selectedKeyword = $this->keywordRepository->findByUid($keyword);
			// Get songs from repository
			$pagetotal = 1;
			$offset = ($pagenow - 1) * intval($this->settings['resultsPerPage']);
			$limit = intval($this->settings['resultsPerPage']);
			$songs = $this->songRepository->findBySingleKeyword($keyword, $limit, $offset);
			$total = $this->songRepository->countBySingleKeyword($keyword);
			
			// Calculate pages numbers and values for pagination
			if ($total > $limit) {
				$pagetotal = ceil($total / $limit);
			}
			$page = array ('now' => $pagenow, 'prev' => max(1,$pagenow-1), 'next' => min($pagetotal,$pagenow+1), 'total' => $pagetotal);
			$pagearray = array();
			if ($pagenow > 7) {
				$pagearray = array (1, 2, 3, '...');
				for ($i=$pagenow-3; $i<=$pagenow; $i++) {
					$pagearray[] = $i;
				}
			} else {
				for ($i=1;$i<=$pagenow;$i++) {
					$pagearray[] = $i;
				}
			}
			if ($pagetotal - $pagenow > 6) {
				$pagearray[] = $pagenow+1;
				$pagearray[] = $pagenow+2;
				$pagearray[] = $pagenow+3;
				$pagearray[] = '...';
				for ($i=$pagetotal-1; $i<=$pagetotal; $i++) {
					$pagearray[] = $i;
				}
			} elseif ($pagenow < $pagetotal) {
				for ($i=$pagenow+1; $i<=$pagetotal; $i++) {
					$pagearray[] = $i;
				}
			}
		} else {
			$song = array();
			$total = 0;
			$page = 1;
			$pagearray = array();
		}

		$this->view->assignMultiple(array(
			'songs' => $songs,
			'total' => $total,
			'page' => $page,
			'pagearray' => $pagearray,
			'keywords' => $keywords,
			'keyword' => $keyword,
			'selectedKeyword' => $selectedKeyword,
			'sources' => $sources,
			'filter' => $filter,
			'filterGottesdienst' => $this->filterGottesdienst,
			'filterHighwaeg' => $this->filterHighwaeg,
			'predefOptions' => $this->searchModes,
			'settings' => $this->settings,
		));
    }
    
    /**
     * action toc (table of content)
     *
     * @return void
     */
    public function tocAction() {
		
		// Get values from search form
		$arguments = $this->request->getArguments();
		if ($arguments['filter']) {
			$filter['source'] = intval($arguments['filter']['source']);
			$filter['sorting'] = $arguments['filter']['sorting'];
			if ($filter['sorting'] != 'title') {
				$filter['sorting'] = 'source';
			}
			$filter['author'] = intval($arguments['filter']['author']);
			$filter['slide'] = intval($arguments['filter']['slide']);
			$filter['lastUsage'] = intval($arguments['filter']['lastUsage']);
			$filter['usages'] = intval($arguments['filter']['usages']);
			$filter['popularity'] = intval($arguments['filter']['popularity']);
			$filter['rejected'] = intval($arguments['filter']['rejected']);
			$filter['copyright'] = intval($arguments['filter']['copyright']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocSource',$filter['source']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocSorting',$filter['sorting']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocAuthor',$filter['author']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocSlide',$filter['slide']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocLastUsage',$filter['lastUsage']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocUsages',$filter['usages']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocPopularity',$filter['popularity']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocRejected',$filter['rejected']);
			$GLOBALS['TSFE']->fe_user->setKey('ses','songTocCopyright',$filter['copyright']);
		} else {
			if ($value = intval($GLOBALS['TSFE']->fe_user->getKey('ses','songTocSource'))) {
				$filter['source'] = $value;
			} else {
				$filter['source'] = 3;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocSorting')) {
				$filter['sorting'] = $value;
			} else {
				$filter['sorting'] = 'source';
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocAuthor')) {
				$filter['author'] = $value;
			} else {
				$filter['author'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocSlide')) {
				$filter['slide'] = $value;
			} else {
				$filter['slide'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocLastUsage')) {
				$filter['lastUsage'] = $value;
			} else {
				$filter['lastUsage'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocUsages')) {
				$filter['usages'] = $value;
			} else {
				$filter['usages'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocPopularity')) {
				$filter['popularity'] = $value;
			} else {
				$filter['popularity'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocRejected')) {
				$filter['rejected'] = $value;
			} else {
				$filter['rejected'] = 0;
			}
			if ($value = $GLOBALS['TSFE']->fe_user->getKey('ses','songTocCopyright')) {
				$filter['copyright'] = $value;
			} else {
				$filter['copyright'] = 0;
			}
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','songToc');

		if ($filter['source'] > 0) {
			// Get songs from repository
			$this->songRepository->setAllSources($this->sourceRepository->findAll());
			$songs = $this->songRepository->findFiltered('', $filter['source'], '1,2,3,4,5,6,7,8,9', 0, 0, $filter['rejected'], $filter['sorting'], 10000, 0);
			$total = count($songs);
			$source = $this->sourceRepository->findByUid($filter['source']);
		}
		
		$sources = $this->sourceRepository->findAll();

		$this->view->assignMultiple(array(
			'songs' => $songs,
			'total' => $total,
			'filter' => $filter,
			'source' => $source,
			'sources' => $sources,
			'settings' => $this->settings,
		));
    }
    
    /**
     * action tocExport (table of content)
     *
     * @return void
     */
    public function tocExportAction() {
		
		// Get values from search form
		$arguments = $this->request->getArguments();
		if ($arguments['filter']) {
			$filter['source'] = intval($arguments['filter']['source']);
			$filter['sorting'] = $arguments['filter']['sorting'];
			if ($filter['sorting'] != 'title') {
				$filter['sorting'] = 'source';
			}
			$filter['author'] = intval($arguments['filter']['author']);
			$filter['slide'] = intval($arguments['filter']['slide']);
			$filter['lastUsage'] = intval($arguments['filter']['lastUsage']);
			$filter['rejected'] = intval($arguments['filter']['rejected']);
		}

		if ($filter['source'] > 0) {
			// Get songs from repository
			$this->songRepository->setAllSources($this->sourceRepository->findAll());
			$songs = $this->songRepository->findFiltered('', $filter['source'], '1,2,3,4,5,6,7,8,9', 0, 0, $filter['rejected'], $filter['sorting'], 10000, 0);
			$total = count($songs);
			$source = $this->sourceRepository->findByUid($filter['source']);
		}

		$now = new \DateTime();

		$filename = 'Inhaltsverzeichnis '.$source->getTitle().'.pdf';
		$this->response->setHeader('Expires','0');
		$this->response->setHeader('Cache-Control','must-revalidate, post-check=0, pre-check=0');
		$this->response->setHeader('Content-Disposition','attachment; filename=' . $filename);
		$this->response->setHeader('Content-Type','application/pdf; charset=UTF8');
		
		$this->view->assignMultiple(array(
			'songs' => $songs,
			'total' => $total,
			'filter' => $filter,
			'source' => $source,
			'now' => $now,
			'filename' => $filename,
		));
    }
    
    /**
     * action show
     *
     * @param Song $song
     * @return void
     */
    public function showAction(\FKU\FkuSongs\Domain\Model\Song $song) {
		$back['action'] = $GLOBALS['TSFE']->fe_user->getKey('ses','songBackAction');
		$back['id'] = $GLOBALS['TSFE']->fe_user->getKey('ses','songBackId');
		$back['pid'] = array ('song' => $this->settings['songPid'], 'reporting' => $this->settings['reportingPid'], 'charts' => $this->settings['chartsPid'], 'statistics' => $this->settings['statisticsPid']);
		$back['default'] = array ('action' => 'list', 'controller' => 'Song', 'plugin' => 'List');
		
		$periods = explode(',',$this->settings['chartsTimeSelection']);
		if (! is_array($periods) or count($periods) == 0) {
			$periods = [3, 6, 12];
		}
		$periodsAll = [];
		$period = 0;

		foreach ($periods as $onePeriod) {
			$history = $this->reportingRepository->findLast($song,$onePeriod);
			if ($history->count() > 0) {
				$lastUsages[$onePeriod] = $history;
				if ($period == 0) {
					$period = $onePeriod;
				}
				$periodsAll[] = $onePeriod;
			}
		}
		if ($period == 0) {
			$period = end((array_values($periods)));
		}
		
		$this->view->assignMultiple(array(
			'song' => $song,
			'lastUsages' => $lastUsages,
			'back' => $back,
			'period' => $period,
			'periods' => array_diff($periods, [$period]),
			'periodsAll' => $periodsAll,
		));
    }
    
    /**
     * action new
     *
     * @param Song $song
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("song")
     * @return void
     */
    public function newAction(\FKU\FkuSongs\Domain\Model\Song $song = NULL) {
		$song = new \FKU\FkuSongs\Domain\Model\Song;
		$sources = $this->sourceRepository->findAll();
		$keywords = $this->keywordRepository->findAll();
		$sourceArray = array(0 => '');
		foreach ($sources as $source) {
			$sourceArray[$source->getUid()] = $source->getAcronym();
		}
		
 		$this->view->assignMultiple(array(
			'song' => $song,
			'sourceArray' => $sourceArray,
			'keywords' => $keywords,
		));
    }
    
    /**
     * action create
     *
     * @param Song $song
     * @return void
     */
    public function createAction(\FKU\FkuSongs\Domain\Model\Song $song) {
		if ($collec = $this->request->getArgument('collection')) {
			$allCollections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
			foreach ($collec as $coll) {
				if ($coll['source'] > 0) {
					$oneCollection = new \FKU\FkuSongs\Domain\Model\Collection;
					$source = $this->sourceRepository->findByUid($coll['source']);
					$oneCollection->setSource($source);
					$oneCollection->setNumber($coll['number']);
					$oneCollection->setSlide($coll['slide']);
					$oneCollection->setRejected($coll['rejected']);
					
					$allCollections->attach($oneCollection);
				}
			}
			$song->setCollection($allCollections);
		}

		// add link
		if ($links = $this->request->getArgument('links')) {
			$this->updateLinks($song, $links);
			// $song->setLinks($allLinks);
		}

        $this->songRepository->add($song);

		// Build slug
		GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
		$record = $this->songRepository->findByUidAssoc($song->getUid());

		$tableName = 'tx_fkusongs_domain_model_song';
		$slugFieldName = 'slug';
		$fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config'];
		$evalInfo = GeneralUtility::trimExplode(',', $fieldConfig['eval'], true);

		$slugHelper = GeneralUtility::makeInstance(SlugHelper::class, $tableName, $slugFieldName, $fieldConfig);
		$slug = $slugHelper->generate($record, $record['pid']);
		$state = RecordStateFactory::forName($tableName)->fromArray($record, $record['pid'], $record['uid']);
		if (in_array('uniqueInSite', $evalInfo)) {
			$slug = $slugHelper->buildSlugForUniqueInSite($slug, $state);
		} else if (in_array('uniqueInPid', $evalInfo)) {
			$slug = $slugHelper->buildSlugForUniqueInPid($slug, $state);
		} else if (in_array('unique', $evalInfo)) {
			$slug = $slugHelper->buildSlugForUniqueInTable($slug, $state);
		}
		$song->setSlug($slug);
		$this->songRepository->update($song);

		$this->addFlashMessage('Lied &quot;'.$song->getTitle().'&quot; eingetragen', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('list');
    }
    
    /**
     * action edit
     *
     * @param Song $song
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("song")
     * @return void
     */
    public function editAction(\FKU\FkuSongs\Domain\Model\Song $song) {
		$sources = $this->sourceRepository->findAll();
		$keywords = $this->keywordRepository->findAll();
		$sourceArray = array(0 => '');
		foreach ($sources as $source) {
			$sourceArray[$source->getUid()] = $source->getAcronym();
		}
		
 		$this->view->assignMultiple(array(
			'song' => $song,
			'sourceArray' => $sourceArray,
			'keywords' => $keywords,
			'linkTypes' => $this->linkTypes,
		));
    }
    
    /**
     * action update
     *
     * @param Song $song
     * @return void
     */
    public function updateAction(\FKU\FkuSongs\Domain\Model\Song $song) {
		if ($collec = $this->request->getArgument('collection')) {
			$allCollections = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
			foreach ($collec as $coll) {
				if ($coll['source'] > 0) {
					$oneCollection = new \FKU\FkuSongs\Domain\Model\Collection;
					$source = $this->sourceRepository->findByUid($coll['source']);
					$oneCollection->setSource($source);
					$oneCollection->setNumber($coll['number']);
					$oneCollection->setSlide($coll['slide']);
					$oneCollection->setRejected($coll['rejected']);
					
					$allCollections->attach($oneCollection);
				}
			}
			$song->setCollection($allCollections);
		}
		if ($links = $this->request->getArgument('links')) {
			$this->updateLinks($song, $links);
		}

		// Build slug
		if ($song->getSlug() == '') {
			$record = $this->songRepository->findByUidAssoc($song->getUid());
	
			$tableName = 'tx_fkusongs_domain_model_song';
			$slugFieldName = 'slug';
			$fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config'];
			$evalInfo = GeneralUtility::trimExplode(',', $fieldConfig['eval'], true);
	
			$slugHelper = GeneralUtility::makeInstance(SlugHelper::class, $tableName, $slugFieldName, $fieldConfig);
			$slug = $slugHelper->generate($record, $record['pid']);
			$state = RecordStateFactory::forName($tableName)->fromArray($record, $record['pid'], $record['uid']);
			if (in_array('uniqueInSite', $evalInfo)) {
				$slug = $slugHelper->buildSlugForUniqueInSite($slug, $state);
			} else if (in_array('uniqueInPid', $evalInfo)) {
				$slug = $slugHelper->buildSlugForUniqueInPid($slug, $state);
			} else if (in_array('unique', $evalInfo)) {
				$slug = $slugHelper->buildSlugForUniqueInTable($slug, $state);
			}
			$song->setSlug($slug);
		}

		$this->songRepository->update($song);
		$this->updateKeywordTable();

        $this->addFlashMessage('Liedinformationen geändert'.$test, '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('show','Song','fkusongs',array('song' => $song));
    }
    
    /**
     * action delete
     *
     * @param Song $song
     * @return void
     */
    public function deleteAction(\FKU\FkuSongs\Domain\Model\Song $song) {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See http://wiki.typo3.org/T3Doc/Extension_Builder/Using_the_Extension_Builder#1._Model_the_domain', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
        $this->songRepository->remove($song);
        $this->redirect('list');
    }

	/**
	* updateKeywordTable
	*
	* @return void
	*/
    protected function updateKeywordTable() {
		$songs = $this->songRepository->findWithKeyword();
		$list = [];
		foreach ($songs as $song) {
			foreach ($song->getKeywordArray() as $keywordId) {
				$list[$keywordId]++;
			}
		}
		$keywords = $this->keywordRepository->findAll();
		foreach ($keywords as $keyword) {
			$keyword->setSongs($list[$keyword->getUid()]);
			$this->keywordRepository->update($keyword);
		}
		return true;
    }


	/**
	* updateLinks
	*
    * @param \FKU\FkuSongs\Domain\Model\Song $song
	* @param \array $links Links information of new/edit form for songs
	* @return void
	*/
    protected function updateLinks($song, $links) {
		if (count($links) > 0) {
			foreach ($links as $link) {
				$sortType[] = $link['type'];
				$sortTitle[] = $link['title'];
			}
			array_multisort($sortType, SORT_ASC, $sortTitle, SORT_ASC, $links);
			$allLinks = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage;
			foreach ($links as $link) {
				if ($link['uid'] == 0) {
					if (trim($link['url']) != '') {
						// create new link
						$oneLink = new \FKU\FkuSongs\Domain\Model\Link;
						$oneLink->setType($link['type']);
						if ($title = trim($link['title'])) {
							$oneLink->setTitle($title);
						} else {
							$oneLink->setTitle($this->linkTypes[$link['type']]);
						}
						$oneLink->setUrl(trim($link['url']));
						$song->addLink($oneLink);
					}
				} else {
					$oneLink = $this->linkRepository->findByUid($link['uid']);
					if (trim($link['url']) == '') {
						// delete existing link
						$this->linkRepository->remove($oneLink);
					} else {
						// modify existing link
						$oneLink->setType($link['type']);
						if ($title = trim($link['title'])) {
							$oneLink->setTitle($title);
						} else {
							$oneLink->setTitle($this->linkTypes[$link['type']]);
						}
						$oneLink->setUrl(trim($link['url']));
						$this->linkRepository->update($oneLink);
					}
				}
			}
		}
	}

}