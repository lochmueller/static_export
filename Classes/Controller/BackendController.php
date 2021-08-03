<?php

/**
 * BackendController.
 */
declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;

/**
 * BackendController.
 */
class BackendController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * Basic backend list.
     */
    public function listAction()
    {
        $this->view->assignMultiple([
        ]);
    }
}
