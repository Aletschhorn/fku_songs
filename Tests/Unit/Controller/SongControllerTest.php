<?php
namespace FKU\FkuSongs\Tests\Unit\Controller;
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
 * Test case for class FKU\FkuSongs\Controller\SongController.
 *
 * @author Daniel Widmer <daniel.widmer@fku.ch>
 */
class SongControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \FKU\FkuSongs\Controller\SongController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('FKU\\FkuSongs\\Controller\\SongController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllSongsFromRepositoryAndAssignsThemToView()
	{

		$allSongs = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$songRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\SongRepository', array('findAll'), array(), '', FALSE);
		$songRepository->expects($this->once())->method('findAll')->will($this->returnValue($allSongs));
		$this->inject($this->subject, 'songRepository', $songRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('songs', $allSongs);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenSongToView()
	{
		$song = new \FKU\FkuSongs\Domain\Model\Song();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('song', $song);

		$this->subject->showAction($song);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenSongToSongRepository()
	{
		$song = new \FKU\FkuSongs\Domain\Model\Song();

		$songRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\SongRepository', array('add'), array(), '', FALSE);
		$songRepository->expects($this->once())->method('add')->with($song);
		$this->inject($this->subject, 'songRepository', $songRepository);

		$this->subject->createAction($song);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenSongToView()
	{
		$song = new \FKU\FkuSongs\Domain\Model\Song();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('song', $song);

		$this->subject->editAction($song);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenSongInSongRepository()
	{
		$song = new \FKU\FkuSongs\Domain\Model\Song();

		$songRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\SongRepository', array('update'), array(), '', FALSE);
		$songRepository->expects($this->once())->method('update')->with($song);
		$this->inject($this->subject, 'songRepository', $songRepository);

		$this->subject->updateAction($song);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenSongFromSongRepository()
	{
		$song = new \FKU\FkuSongs\Domain\Model\Song();

		$songRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\SongRepository', array('remove'), array(), '', FALSE);
		$songRepository->expects($this->once())->method('remove')->with($song);
		$this->inject($this->subject, 'songRepository', $songRepository);

		$this->subject->deleteAction($song);
	}
}
