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
 * ReportingController
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use FKU\FkuSongs\Domain\Repository\SongRepository;
use FKU\FkuSongs\Domain\Repository\SourceRepository;
use FKU\FkuSongs\Domain\Repository\ReportingRepository;
use FKU\FkuSongs\Domain\Repository\EventRepository;
use FKU\FkuPlanning\Domain\Repository\MasterRepository;
use FKU\FkuAgenda\Domain\Repository\VisibilityCategoryRepository;

class ReportingController extends ActionController {

    /**
     * reportingRepository
     *
     * @var ReportingRepository
     */
    protected $reportingRepository;
    
    /**
     * songRepository
     *
     * @var SongRepository
     */
    protected $songRepository;
    
    /**
     * eventRepository
     *
     * @var EventRepository
     */
    protected $eventRepository;
    
	/**
	 * sourceRepository
	 *
	 * @var SourceRepository
	 */
	protected $sourceRepository;

	/**
	 * @param SongRepository $songRepository
	 * @param SourceRepository $sourceRepository
	 * @param ReportingRepository $reportingRepository
	 * @param EventRepository $eventRepository
	 */
	public function __construct(
			SongRepository $songRepository,
			SourceRepository $sourceRepository,
			ReportingRepository $reportingRepository,
			EventRepository $eventRepository
		) {
		$this->songRepository = $songRepository;
		$this->sourceRepository = $sourceRepository;
		$this->reportingRepository = $reportingRepository;
		$this->eventRepository = $eventRepository;
	}


    /**
     * action list
     *
     * @return void
     */
    public function listAction() {
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','reportList');
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackId','');
		
        $reportings = $this->eventRepository->findLastEventsWithSongs('',3,true);
		
		$timestamp = strtotime('-1 month');
		$events = $this->eventRepository->findByInterval($timestamp,45,0,array(1),0,array(21,24));
		$newEvents = array_diff($events->toArray(),$reportings->toArray());
		
		$this->view->assignMultiple(array(
			'reportings' => $reportings,
			'newEvents' => $newEvents,
			'settings' => $this->settings,
		));
    }
    
    /**
     * action statistics
     *
     * @return void
     */
    public function statisticsAction() {
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','reportShowStatistics');
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackId','');

		$filter = $GLOBALS['TSFE']->fe_user->getKey('ses','songEventCategories');
		if ($this->request->hasArgument('filter')) {
			$filter = $this->request->getArgument('filter');
		}
		if ($filter == '0') {
			$filter = '';
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songEventCategories',$filter);

		$timeSelection = explode(',',$this->settings['chartsTimeSelection']);
		if (! is_array($timeSelection) or count($timeSelection) == 0) {
			$timeSelection = [3, 6, 12];
		}
		$period = $GLOBALS['TSFE']->fe_user->getKey('ses','songPeriod');
		if ($this->request->hasArgument('period')) {
			$period = intval($this->request->getArgument('period'));
		}
		if (! in_array($period, $timeSelection)) {
			$period = $timeSelection[0];
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songPeriod',$period);

		$sum = 0;
		$statistic = [];
		$songs = [];
        $reportings = $this->eventRepository->findLastEventsWithSongs($filter, $period);
		foreach ($reportings as $event) {
			foreach ($event->getSongreporting() as $reporting) {
				$sum++;
				$statistic['status'][$reporting->getStatus()][] = $reporting->getSong();
				$statistic['language'][$reporting->getSong()->getLanguage()][] = $reporting->getSong();
				$song = $reporting->getSong();
				if (! in_array($song->getUid(), $songs)) {
					$songs[] = $song->getUid();
					$statistic['ccli'][$song->getCcliOffering()][] = $song;
					$statistic['keywords'][count($song->getKeywordArray())][] = $song;
					if ($song->getSongtext() == '') {
						$statistic['songtext'][0][] = $song;
					} else {
						$statistic['songtext'][1][] = $song;
					}
				}
			}
		}
		$sumUnique = count($songs);

		foreach ($statistic as $key1 => $stat1) {
			ksort($stat1);
			foreach ($stat1 as $key2 => $stat2) {
				sort($stat2);
				$statisticSorted[$key1][$key2] = $stat2;
			}
		}
		
		$this->view->assignMultiple(array(
			'reportings' => $reportings,
			'sum' => $sum,
			'sumUnique' => $sumUnique,
			'statistic' => $statisticSorted,
			'filter' => $filter,
			'period' => $period,
			'timeSelection' => $timeSelection,
			'settings' => $this->settings,
		));
    }

    /**
     * action open
     *
     * @return void
     */
    public function openAction() {
        $reportings = $this->reportingRepository->findByStatus(0);

		$this->view->assignMultiple(array(
			'reportings' => $reportings,
			'settings' => $this->settings,
		));

	}
        
    /**
     * action complete
     *
     * @return void
     */
    public function completeAction() {
		if ($this->request->hasArgument('reporting')) {
			$reportings = $this->request->getArgument('reporting');
		}
		
		if (count($reportings)) {
			foreach ($reportings as $singleReporting) {
				$reporting = $this->reportingRepository->findByUid($singleReporting);
				if ($reporting->getStatus() == 0) {
					$reporting->setStatus(5);
					$this->reportingRepository->update($reporting);
				}
			}
		}

        $this->redirect('open');
			
	}
        
    /**
     * action showEvent
     *
     * @param \FKU\FkuAgenda\Domain\Model\Event $event
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @return void
     */
    public function showEventAction(\FKU\FkuAgenda\Domain\Model\Event $event) {
		$back['action'] = $GLOBALS['TSFE']->fe_user->getKey('ses','songBackAction');
		$back['id'] = $GLOBALS['TSFE']->fe_user->getKey('ses','songBackId');
		$back['pid'] = array ('song' => $this->settings['songPid'], 'reporting' => $this->settings['reportingPid'], 'charts' => $this->settings['chartsPid'], 'statistics' => $this->settings['statisticsPid']);
		$back['default'] = array ('action' => 'list', 'controller' => 'Reporting');

		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','reportShowEvent');
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackId',$event->getUid());

		$reporting = $this->reportingRepository->findByEvent($event);
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$master = $objectManager->get(MasterRepository::class)->findByEvent($event);		
		
		$this->view->assignMultiple(array(
			'reporting' => $reporting,
			'event' => $event,
			'master' => $master,
			'settings' => $this->settings,
			'back' => $back,
		));
    }

    
    /**
     * action showCharts
     *
     * @return void
     */
    public function showChartsAction() {
		$back['pid'] = array ('song' => $this->settings['songPid'], 'reporting' => $this->settings['reportingPid'], 'charts' => $this->settings['chartsPid'], 'statistics' => $this->settings['statisticsPid']);
		$back['default'] = array ('action' => 'list', 'controller' => 'Reporting', 'plugin' => 'Reporting');

		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackAction','ABCreportShowCharts');
		$GLOBALS['TSFE']->fe_user->setKey('ses','songBackId','');

		$filter = $GLOBALS['TSFE']->fe_user->getKey('ses','songEventCategories');
		if ($this->request->hasArgument('filter')) {
			$filter = $this->request->getArgument('filter');
		}
		if ($filter == '0') {
			$filter = '';
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songEventCategories',$filter);

		$timeSelection = explode(',',$this->settings['chartsTimeSelection']);
		if (! is_array($timeSelection) or count($timeSelection) == 0) {
			$timeSelection = [3, 6, 12];
		}
		$period = $GLOBALS['TSFE']->fe_user->getKey('ses','songPeriod');
		if ($this->request->hasArgument('period')) {
			$period = intval($this->request->getArgument('period'));
		}
		if (! in_array($period, $timeSelection)) {
			$period = $timeSelection[0];
		}
		$GLOBALS['TSFE']->fe_user->setKey('ses','songPeriod',$period);

		$charts = $this->reportingRepository->findByUsage($filter, $period);
		
		$this->view->assignMultiple(array(
			'charts' => $charts,
			'filter' => $filter,
			'period' => $period,
			'timeSelection' => $timeSelection,
			'settings' => $this->settings,
			'back' => $back,
		));
    }

    
    /**
     * action show
     *
     * @param Reporting $reporting
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("reporting")
     * @return void
     */
    public function showAction(\FKU\FkuSongs\Domain\Model\Reporting $reporting) {
        $this->view->assign('reporting', $reporting);
    }
    
    /**
     * action newEvent
     *
     * @param \FKU\FkuAgenda\Domain\Model\Event $event
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @return void
     */
    public function newEventAction(\FKU\FkuAgenda\Domain\Model\Event $event) {
		$this->view->assignMultiple(array(
			'event' => $event,
			'settings' => $this->settings,
		));
        
    }
    
     /**
     * action new
     *
     * @param \FKU\FkuAgenda\Domain\Model\Event $event
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @return void
     */
    public function newAction(\FKU\FkuAgenda\Domain\Model\Event $event) {
		
		$sources = $this->sourceRepository->findAll();

		// Initialize
		$source = '0';
		foreach ($sources as $single) {
			$source .= ','.intval($single->getUid());
		}
		$filter = array (
			'searchword' => '', 
			'source' => $source, 
			'language' => '1,2,3,9',
			'recommended' => 0,
			'lyrics' => 0,
			'sorting' => 'title'
		);
		$pagenow = 1;

		// Get values from search form and pagination
		if ($this->request->hasArgument('filter')) { 
			$tempFilter = $this->request->getArgument('filter');
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
			$filter['searchword'] = $tempFilter['searchword'];
			$filter['recommended'] = $tempFilter['recommended'];
			$filter['lyrics'] = $tempFilter['lyrics'];
			$filter['sorting'] = $tempFilter['sorting'];
		}
		if ($this->request->hasArgument('page')) { $pagenow = intval($this->request->getArgument('page')); }
		
		// Get songs from repository
		$this->songRepository->setAllSources($this->sourceRepository->findAll());
		$pagetotal = 1;
		$offset = ($pagenow - 1) * intval($this->settings['resultsPerPage']);
		$limit = intval($this->settings['resultsPerPage']);
		$songs = $this->songRepository->findFiltered($filter['searchword'], $filter['source'], $filter['language'], $filter['recommended'], $filter['lyrics'], true, $filter['sorting'], $limit, $offset);
		$total = $this->songRepository->countFiltered($filter['searchword'], $filter['source'], $filter['language'], $filter['recommended'], $filter['lyrics'], true);
		
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

		$this->view->assignMultiple(array(
			'songs' => $songs,
			'total' => $total,
			'page' => $page,
			'pagearray' => $pagearray,
			'sources' => $sources,
			'filter' => $filter,
			'event' => $event,
			'settings' => $this->settings,
			'tempFilter' => $tempFilter,
		));
        
    }
    
   /**
     * action create
     *
     * @param int $event
     * @param string $choice
     * @return void
     */
    public function createAction($event, $choice) {

		$parts = explode('-',$choice);
		$song = intval($parts[0]);
		$next = $parts[1];
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting;
		$eventObject = $this->eventRepository->findByUid($event);
		$songObject = $this->songRepository->findByUid($song);
		if ($songObject and $eventObject) {
			$reporting->setEvent($eventObject);
			$reporting->setSong($songObject);
			$reporting->setStatus(0);
			if ($songObject->getCcliOffering() == 1) {
				$reporting->setStatus(2);
			}
			if ($songObject->getCopyright() == 1) {
				$reporting->setStatus(3);
			}
			if ($songObject->getCopyright() == 2) {
				$reporting->setStatus(4);
			}
			if ($songObject->getCopyright() == 3) {
				$reporting->setStatus(7);
			}
			
	        $this->reportingRepository->add($reporting);
			
			$eventObject->addSongreporting($reporting);
			$this->eventRepository->update($eventObject);
			
			$soFarAddedSongs = $eventObject->getSongreporting();
			if ($next == 'new') {
				$soFarAddedSongsList .= 'Bisher für Anlass erfasst:<br /><ul>';
				foreach ($soFarAddedSongs as $soFarSong) {
					$soFarAddedSongsList .= '<li>' . $soFarSong->getSong()->getTitle() . '</li>';
				}
				$soFarAddedSongsList .= '</ul>';
		        $this->addFlashMessage($soFarAddedSongsList, '&quot;'.$songObject->getTitle().'&quot; hinzugefügt', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
			} else {
		        $this->addFlashMessage('&quot;'.$songObject->getTitle().'&quot; hinzugefügt', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
			}
			
		} else {
	        $this->addFlashMessage('Lied hinzufügen fehlgeschlagen', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
		}
		
		if ($this->request->hasArgument('filter')) {
			$filter = $this->request->getArgument('filter');
		}
        $this->redirect($next,'Reporting','fkusongs',array('event' => $eventObject, 'filter' => $filter));
    }
    
    /**
     * action editEvent
     *
     * @param \FKU\FkuAgenda\Domain\Model\Event $event
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("event")
     * @return void
     */
    public function editEventAction(\FKU\FkuAgenda\Domain\Model\Event $event) {
		$reporting = $this->reportingRepository->findByEvent($event);
		$status = array(
			0 => 'Offen',
			1 => 'Nicht gezeigt (ohne Beamer)',
			2 => 'Nicht möglich (nicht in CCLI)',
			3 => 'Unnötig da Public Domain',
			4 => 'Unnötig da FKU-eigen',
			7 => 'Unnötig da freie Verwendung',
			6 => 'Anderswo gekauft',
			5 => 'Erledigt'
		);

		$this->view->assignMultiple(array(
			'reporting' => $reporting,
			'event' => $event,
			'status' => $status,
			'settings' => $this->settings,
		));
    }
    
    /**
     * action updateEvent
     *
     * @return void
     */
    public function updateEventAction() {
		if ($this->request->hasArgument('status')) {
			$error = false;
			$status = $this->request->getArgument('status');
			foreach ($status as $id => $stat) {
				if ($reporting = $this->reportingRepository->findByUid($id)) {
					$reporting->setStatus($stat);
					$this->reportingRepository->update($reporting);
				} else {
					$error = true;
				}
			}
			if ($error) {
		        $this->addFlashMessage('Fehler beim Speichern', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
			} else {
		        $this->addFlashMessage('Änderungen gespeichert', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
			}
		} else {
	        $this->addFlashMessage('Keine Änderungen erkannt', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::INFO);
		}
		if ($this->request->hasArgument('event')) {
			$event = $this->request->getArgument('event');
	        $this->redirect('showEvent','Reporting','fkusongs',array('event' => $event));
		} else {
			$this->redirect('list');
		}
    }
    
    /**
     * action delete
     *
     * @param Reporting $reporting
	 * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("reporting")
     * @return void
     */
    public function deleteAction(\FKU\FkuSongs\Domain\Model\Reporting $reporting) {
        $event = $reporting->getEvent();
		$this->reportingRepository->remove($reporting);
        $this->addFlashMessage('Lied aus Liste gelöscht', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
        $this->redirect('editEvent','Reporting','fkusongs',array('event' => $event));
    }

}