<?php
namespace TYPO3\CMS\Extensionmanager\Tests\Unit\Controller;

/***************************************************************
 * Copyright notice
 *
 * (c) 2012-2013 Susanne Moog, <typo3@susannemoog.de>
 * All rights reserved
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Update from TER controller test
 *
 */
class UpdateFromTerControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $mockObjectManager;

	/**
	 * @var \TYPO3\CMS\Extensionmanager\Domain\Repository\RepositoryRepository
	 */
	protected $repositoryRepositoryMock;

	/**
	 * @var \TYPO3\CMS\Extensionmanager\Domain\Repository\ExtensionRepository
	 */
	protected $extensionRepositoryMock;

	/**
	 * @var \TYPO3\CMS\Extensionmanager\Utility\Repository\Helper
	 */
	protected $repositoryHelperMock;

	public function setUp() {
		$this->mockObjectManager = $this->getMock('TYPO3\\CMS\\Extbase\\Object\\ObjectManagerInterface');
		$this->repositoryRepositoryMock = $this->getMock('TYPO3\\CMS\\Extensionmanager\\Domain\\Repository\\RepositoryRepository', array('findByUid'), array($this->mockObjectManager));
		$this->extensionRepositoryMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Domain\\Repository\\ExtensionRepository', array(), array($this->mockObjectManager));
		$this->repositoryHelperMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Utility\\Repository\\Helper', array('updateExtList'), array(), '', FALSE);
	}

	/**
	 * @test
	 * @return void
	 */
	public function updateExtensionListFromTerCallsUpdateExtListIfExtensionListIsEmpty() {
		$controllerMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Controller\\UpdateFromTerController', array('dummy'));
		$repositoryModelMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Domain\\Model\\Repository', array('getLastUpdate'));

		$viewMock = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array('assign'));
		$requestMock = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Request', array('hasArgument', 'getArgument'));
		$viewMock->expects($this->any())->method('assign')->will($this->returnValue($viewMock));
		$this->repositoryRepositoryMock->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($repositoryModelMock));
		$this->repositoryHelperMock->expects($this->once())->method('updateExtList');
		$this->extensionRepositoryMock->expects($this->once())->method('countAll')->will($this->returnValue(0));
		$controllerMock->_set('extensionRepository', $this->extensionRepositoryMock);
		$controllerMock->_set('repositoryRepository', $this->repositoryRepositoryMock);
		$controllerMock->_set('repositoryHelper', $this->repositoryHelperMock);
		$controllerMock->_set('settings', array('repositoryUid' => 1));
		$controllerMock->_set('view', $viewMock);
		$controllerMock->_set('request', $requestMock);
		$controllerMock->updateExtensionListFromTerAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function updateExtensionListFromTerDoesNotCallsUpdateExtListIfExtensionListIsNotEmpty() {
		$controllerMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Controller\\UpdateFromTerController', array('dummy'));
		$repositoryModelMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Domain\\Model\\Repository', array('getLastUpdate'));
		$viewMock = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array('assign'));
		$requestMock = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Request', array('hasArgument', 'getArgument'));
		$viewMock->expects($this->any())->method('assign')->will($this->returnValue($viewMock));
		$this->repositoryRepositoryMock->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($repositoryModelMock));
		$this->repositoryHelperMock->expects($this->never())->method('updateExtList');
		$this->extensionRepositoryMock->expects($this->once())->method('countAll')->will($this->returnValue(100));
		$controllerMock->_set('extensionRepository', $this->extensionRepositoryMock);
		$controllerMock->_set('repositoryRepository', $this->repositoryRepositoryMock);
		$controllerMock->_set('repositoryHelper', $this->repositoryHelperMock);
		$controllerMock->_set('settings', array('repositoryUid' => 1));
		$controllerMock->_set('view', $viewMock);
		$controllerMock->_set('request', $requestMock);
		$controllerMock->updateExtensionListFromTerAction();
	}

	/**
	 * @test
	 * @return void
	 */
	public function updateExtensionListFromTerCallsUpdateExtListIfForceUpdateCheckIsSet() {
		$controllerMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Controller\\UpdateFromTerController', array('dummy'));
		$repositoryModelMock = $this->getAccessibleMock('TYPO3\\CMS\\Extensionmanager\\Domain\\Model\\Repository', array('getLastUpdate'));
		$viewMock = $this->getAccessibleMock('TYPO3\\CMS\\Fluid\\View\\TemplateView', array('assign'));
		$requestMock = $this->getAccessibleMock('TYPO3\\CMS\\Extbase\\Mvc\\Request', array('hasArgument', 'getArgument'));
		$viewMock->expects($this->any())->method('assign')->will($this->returnValue($viewMock));
		$this->repositoryRepositoryMock->expects($this->once())->method('findByUid')->with(1)->will($this->returnValue($repositoryModelMock));
		$this->repositoryHelperMock->expects($this->once())->method('updateExtList');
		$this->extensionRepositoryMock->expects($this->once())->method('countAll')->will($this->returnValue(100));
		$controllerMock->_set('extensionRepository', $this->extensionRepositoryMock);
		$controllerMock->_set('repositoryRepository', $this->repositoryRepositoryMock);
		$controllerMock->_set('repositoryHelper', $this->repositoryHelperMock);
		$controllerMock->_set('settings', array('repositoryUid' => 1));
		$controllerMock->_set('view', $viewMock);
		$controllerMock->_set('request', $requestMock);
		$controllerMock->updateExtensionListFromTerAction(TRUE);
	}

}
