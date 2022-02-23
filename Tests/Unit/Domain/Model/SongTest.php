<?php

namespace FKU\FkuSongs\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2016 Daniel Widmer <daniel.widmer@fku.ch>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * Test case for class \FKU\FkuSongs\Domain\Model\Song.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Daniel Widmer <daniel.widmer@fku.ch>
 */
class SongTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{
	/**
	 * @var \FKU\FkuSongs\Domain\Model\Song
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = new \FKU\FkuSongs\Domain\Model\Song();
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTitleReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTitle()
		);
	}

	/**
	 * @test
	 */
	public function setTitleForStringSetsTitle()
	{
		$this->subject->setTitle('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'title',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLanguageReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setLanguageForIntSetsLanguage()
	{	}

	/**
	 * @test
	 */
	public function getAuthorReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getAuthor()
		);
	}

	/**
	 * @test
	 */
	public function setAuthorForStringSetsAuthor()
	{
		$this->subject->setAuthor('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'author',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getYearReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setYearForIntSetsYear()
	{	}

	/**
	 * @test
	 */
	public function getBpmReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setBpmForIntSetsBpm()
	{	}

	/**
	 * @test
	 */
	public function getToneReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getTone()
		);
	}

	/**
	 * @test
	 */
	public function setToneForStringSetsTone()
	{
		$this->subject->setTone('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'tone',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCcliIdReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setCcliIdForIntSetsCcliId()
	{	}

	/**
	 * @test
	 */
	public function getCcliOfferingReturnsInitialValueForInt()
	{	}

	/**
	 * @test
	 */
	public function setCcliOfferingForIntSetsCcliOffering()
	{	}

	/**
	 * @test
	 */
	public function getPublicdomainReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getPublicdomain()
		);
	}

	/**
	 * @test
	 */
	public function setPublicdomainForStringSetsPublicdomain()
	{
		$this->subject->setPublicdomain('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'publicdomain',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinkLyricsReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getLinkLyrics()
		);
	}

	/**
	 * @test
	 */
	public function setLinkLyricsForStringSetsLinkLyrics()
	{
		$this->subject->setLinkLyrics('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'linkLyrics',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinkChordsReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getLinkChords()
		);
	}

	/**
	 * @test
	 */
	public function setLinkChordsForStringSetsLinkChords()
	{
		$this->subject->setLinkChords('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'linkChords',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getLinkLeadsheetReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getLinkLeadsheet()
		);
	}

	/**
	 * @test
	 */
	public function setLinkLeadsheetForStringSetsLinkLeadsheet()
	{
		$this->subject->setLinkLeadsheet('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'linkLeadsheet',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCommentReturnsInitialValueForString()
	{
		$this->assertSame(
			'',
			$this->subject->getComment()
		);
	}

	/**
	 * @test
	 */
	public function setCommentForStringSetsComment()
	{
		$this->subject->setComment('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'comment',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getRelationReturnsInitialValueForSong()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getRelation()
		);
	}

	/**
	 * @test
	 */
	public function setRelationForSongSetsRelation()
	{
		$relationFixture = new \FKU\FkuSongs\Domain\Model\Song();
		$this->subject->setRelation($relationFixture);

		$this->assertAttributeEquals(
			$relationFixture,
			'relation',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getCollectionReturnsInitialValueForCollection()
	{
		$this->assertEquals(
			NULL,
			$this->subject->getCollection()
		);
	}

	/**
	 * @test
	 */
	public function setCollectionForCollectionSetsCollection()
	{
		$collectionFixture = new \FKU\FkuSongs\Domain\Model\Collection();
		$this->subject->setCollection($collectionFixture);

		$this->assertAttributeEquals(
			$collectionFixture,
			'collection',
			$this->subject
		);
	}
}
