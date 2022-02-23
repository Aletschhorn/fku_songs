<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,
		'delete' => 'deleted',
		'default_sortby' => 'ORDER BY title',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,language,author,year,ccli_id,comment,last_usage',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_song.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, title, language, recommended, author, year, bpm, tone, collection, ccli_id, ccli_offering, copyright, youtube, songtext, comment, relation, keywords, last_usage, popularity, usages, statistic_update',
	),
	'types' => array(
		'1' => array('showitem' => '--palette--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:palette.general;general, slug, collection, keywords, songtext, comment, --palette--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:palette.statistics;statistics, --div--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tab.relations,--palette--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:palette.ccli;ccli, links, youtube, relation, relation_from, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, hidden, --palette--;;1, starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
		'general' => array('showitem' => 'title, language, recommended, --linebreak--, author, year, bpm, tone'),
		'ccli' => array('showitem' => 'ccli_offering, ccli_id, copyright'),
		'statistics' => array('showitem' => 'last_usage, usages, popularity, statistic_update'),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'language' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.language',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Deutsch', 1),
					array('Schweizerdeutsch', 2),
					array('Englisch', 3),
					array('Andere', 9),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'author' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.author',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'year' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.year',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'bpm' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.bpm',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'tone' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.tone',
			'config' => array(
				'type' => 'input',
				'size' => 10,
				'eval' => 'trim'
			),
		),
		'keywords' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.keywords',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectMultipleSideBySide',
				'foreign_table' => 'tx_fkusongs_domain_model_keyword',
				'foreign_table_where' => 'ORDER BY tx_fkusongs_domain_model_keyword.title',
				'size' => 5,
				'minitems' => 0,
				'maxitems' => 20,
			),
		),
		'ccli_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.ccli_id',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int'
			)
		),
		'ccli_offering' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.ccli_offering',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Unbekannt', 0),
					array('Nicht vorhanden', 1),
					array('Text', 2),
					array('Akkorde', 3),
					array('Noten', 4),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'copyright' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.copyright',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'items' => array(
					array('Normal', 0),
					array('Public Domain', 1),
					array('FKU-eigen', 2),
					array('Freie Verwendung', 3),
				),
				'size' => 1,
				'maxitems' => 1,
				'eval' => ''
			),
		),
		'links' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.links',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_fkusongs_domain_model_link',
				'foreign_field' => 'song',
				'maxitems' => 10,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
				),
			),
		),
		'youtube' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.youtube',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'eval' => 'trim'
			),
		),
		'comment' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.comment',
			'config' => array(
				'type' => 'text',
				'cols' => 40,
				'rows' => 5,
				'eval' => 'trim'
			)
		),
		'relation' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.relation',
			'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'allowed' => 'tx_fkusongs_domain_model_song',
                'foreign_table' => 'tx_fkusongs_domain_model_song',
                'MM_opposite_field' => 'related_from',
                'size' => 3,
                'minitems' => 0,
                'maxitems' => 10,
                'MM' => 'tx_fkusongs_song_related_mm',
                'suggestOptions' => array(
					'type' => 'suggest',
					'default' => array(
						'searchWholePhrase' => true
					)
                ),
			),
		),
        'relation_from' => array(
            'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.relation_from',
            'config' => array(
                'type' => 'group',
                'internal_type' => 'db',
                'foreign_table' => 'tx_fkusongs_domain_model_song',
                'allowed' => 'tx_fkusongs_domain_model_song',
                'size' => 3,
                'maxitems' => 10,
                'MM' => 'tx_fkusongs_song_related_mm',
                'readOnly' => 1,
            )
        ),
		'collection' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.collection',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_fkusongs_domain_model_collection',
				'MM' => 'tx_fkusongs_song_collection_mm',
				'minitems' => 0,
				'maxitems' => 3,
				'mulitple' => 1,
				'appearance' => array(
					'collapseAll' => 0,
					'levelLinksPosition' => 'top',
					'showSynchronizationLink' => 1,
				),
			),
		),
		'recommended' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.recommended',
			'config' => array(
				'type' => 'check',
			),
		),
		'last_usage' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.last_usage',
			'config' => array(
				'dbType' => 'datetime',
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 12,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => '0000-00-00 00:00:00'
			),
		),
		'songtext' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.songtext',
			'config' => array(
				'type' => 'text',
				'cols' => 80,
				'rows' => 20,
				'eval' => 'trim'
			)
		),
		'popularity' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.popularity',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'eval' => 'trim',
				'checkbox' => 0,
				'default' => 0,
			),
		),
		'usages' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.usages',
			'config' => array(
				'type' => 'input',
				'size' => 8,
				'eval' => 'trim,int',
				'checkbox' => 0,
				'default' => 0,
			),
		),
		'statistic_update' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.statistic_update',
			'config' => array(
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'dbType' => 'date',
				'eval' => 'date',
				'size' => 12,
				'checkbox' => 0,
			),
		),
		'slug' => [
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_song.slug',
			'config' => [
				'type' => 'slug',
				'generatorOptions' => [
					'fields' => ['title'],
					'replacements' => [
						',' => '',
						'.' => '',
					],
				],
				'appearance' => [
					'prefix' => \FKU\FkuSongs\UserFunctions\FormEngine\SlugPrefix::class . '->getPrefix'
				],
				'fallbackCharacter' => '-',
				'eval' => 'unique',
				'default' => '',
			],
		],
		
	),
);