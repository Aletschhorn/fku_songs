<?php
$newEventCategories = array(
	'songreporting' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkuagenda_domain_model_event.songreporting',
		'config' => array(
			'type' => 'inline',
			'foreign_table' => 'tx_fkusongs_domain_model_reporting',
			'foreign_field' => 'event',
			'minitems' => 0,
			'maxitems' => 50,
			'mulitple' => 1,
			'appearance' => array(
				'collapseAll' => 0,
				'levelLinksPosition' => 'top',
				'showSynchronizationLink' => 1,
				'levelLinksPosition' => 'both',
			)
		)
	)
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_fkuagenda_domain_model_event', $newEventCategories);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_fkuagenda_domain_model_event',
    '--div--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tab.songreporting, songreporting');

?>