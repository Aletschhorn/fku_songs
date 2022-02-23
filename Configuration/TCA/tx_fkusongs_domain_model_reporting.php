<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_reporting',
		'label' => 'song',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,
		'delete' => 'deleted',
		'enablecolumns' => array(),
		'searchFields' => 'status,song,',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_reporting.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'status, song, event',
	),
	'types' => array(
		'1' => array('showitem' => '--palette--;;1'),
	),
	'palettes' => array(
		'1' => array('showitem' => 'song, status'),
	),
	'columns' => array(
		'song' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_reporting.song',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_fkusongs_domain_model_song',
				'foreign_table_where' => 'ORDER BY tx_fkusongs_domain_model_song.title',
				'minitems' => 0,
				'maxitems' => 1,
                'wizards' => array(
                    'suggest' => array(
                        'type' => 'suggest',
                        'default' => array(
                            'searchWholePhrase' => true
                        )
                    ),
                ),
			),
		),
		'event' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_reporting.event',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_fkuagenda_domain_model_event',
				'minitems' => 0,
				'maxitems' => 1,
				'readOnly' => 1,
			),
		),
		'status' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_reporting.status',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Offen', 0),
					array('Nicht gezeigt (ohne Beamer)', 1),
					array('Nicht möglich (nicht in CCLI)', 2),
					array('Unnötig da Public Domain', 3),
					array('Unnötig da FKU-eigen', 4),
					array('Anderswo gekauft', 6),
					array('Erledigt', 5),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		
	),
);