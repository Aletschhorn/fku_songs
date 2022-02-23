<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_link',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,
		'default_sortby' => 'ORDER BY song, type',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'song, title',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_link.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, url, type, title, song,',
	),
	'types' => array(
		'1' => array('showitem' => 'song, --palette--;;general'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
		'general' => array('showitem' => 'type, title, url'),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'song' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_link.url',
			'config' => array(
				'type' => 'none',
			),
		),
		'url' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_link.url',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_link.type',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Liedtext', 0),
					array('Akkorde', 1),
					array('Noten', 2),
					array('Präsentation', 3),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_link.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),

	),
);