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
 * Test case for class FKU\FkuSongs\Controller\ReportingController.
 *
 * @author Daniel Widmer <daniel.widmer@fku.ch>
 */
class ReportingControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase
{

	/**
	 * @var \FKU\FkuSongs\Controller\ReportingController
	 */
	protected $subject = NULL;

	public function setUp()
	{
		$this->subject = $this->getMock('FKU\\FkuSongs\\Controller\\ReportingController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	public function tearDown()
	{
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllReportingsFromRepositoryAndAssignsThemToView()
	{

		$allReportings = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$reportingRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\ReportingRepository', array('findAll'), array(), '', FALSE);
		$reportingRepository->expects($this->once())->method('findAll')->will($this->returnValue($allReportings));
		$this->inject($this->subject, 'reportingRepository', $reportingRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('reportings', $allReportings);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}

	/**
	 * @test
	 */
	public function showActionAssignsTheGivenReportingToView()
	{
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('reporting', $reporting);

		$this->subject->showAction($reporting);
	}

	/**
	 * @test
	 */
	public function createActionAddsTheGivenReportingToReportingRepository()
	{
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting();

		$reportingRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\ReportingRepository', array('add'), array(), '', FALSE);
		$reportingRepository->expects($this->once())->method('add')->with($reporting);
		$this->inject($this->subject, 'reportingRepository', $reportingRepository);

		$this->subject->createAction($reporting);
	}

	/**
	 * @test
	 */
	public function editActionAssignsTheGivenReportingToView()
	{
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting();

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$this->inject($this->subject, 'view', $view);
		$view->expects($this->once())->method('assign')->with('reporting', $reporting);

		$this->subject->editAction($reporting);
	}

	/**
	 * @test
	 */
	public function updateActionUpdatesTheGivenReportingInReportingRepository()
	{
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting();

		$reportingRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\ReportingRepository', array('update'), array(), '', FALSE);
		$reportingRepository->expects($this->once())->method('update')->with($reporting);
		$this->inject($this->subject, 'reportingRepository', $reportingRepository);

		$this->subject->updateAction($reporting);
	}

	/**
	 * @test
	 */
	public function deleteActionRemovesTheGivenReportingFromReportingRepository()
	{
		$reporting = new \FKU\FkuSongs\Domain\Model\Reporting();

		$reportingRepository = $this->getMock('FKU\\FkuSongs\\Domain\\Repository\\ReportingRepository', array('remove'), array(), '', FALSE);
		$reportingRepository->expects($this->once())->method('remove')->with($reporting);
		$this->inject($this->subject, 'reportingRepository', $reportingRepository);

		$this->subject->deleteAction($reporting);
	}
}
