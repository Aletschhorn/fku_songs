<?php
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use FKU\FkuSongs\Controller\SongController;
use FKU\FkuSongs\Controller\ReportingController;

defined('TYPO3_MODE') or die();

(static function() {
	ExtensionUtility::configurePlugin(
		'FkuSongs',
		'List',
		[SongController::class => 'list, keyword, toc, tocExport, show, new, create, edit, update, delete'],
		[SongController::class => 'list, toc, tocExport, show, new, create, update, delete']
	);
	
	ExtensionUtility::configurePlugin(
		'FkuSongs',
		'Reporting',
		[ReportingController::class => 'list, show, showEvent, showSong, new, newEvent, create, editEvent, updateEvent, delete, showCharts'],
		[ReportingController::class => 'list, new, create, update, delete']
	);
	
	ExtensionUtility::configurePlugin(
		'FkuSongs',
		'Charts',
		[ReportingController::class => 'showCharts'],
		[]
	);
	
	ExtensionUtility::configurePlugin(
		'FkuSongs',
		'Statistics',
		[ReportingController::class => 'statistics, open, complete'],
		[ReportingController::class => 'open, complete']
	);
	
	ExtensionUtility::configurePlugin(
		'FkuSongs',
		'Slugs',
		[SongController::class => 'createSlugs'],
		[SongController::class => 'createSlugs']
	);
})();

$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\FKU\FkuAgenda\Domain\Model\Event::class] = [
    'className' => \FKU\FkuSongs\Domain\Model\Event::class,
];
// Register extended domain class
\TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Object\Container\Container::class)
    ->registerImplementation(
        \FKU\FkuAgenda\Domain\Model\Event::class,
        \FKU\FkuSongs\Domain\Model\Event::class
    );
	
?>