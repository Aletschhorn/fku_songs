<?php
declare(strict_types = 1);

namespace FKU\FkuSongs\UserFunctions\FormEngine;

use TYPO3\CMS\Backend\Form\FormDataProvider\TcaSlug;

class SlugPrefix {
    public function getPrefix(array $parameters, TcaSlug $reference): string {
        return $parameters['site']->getBase().'lieder/';
    }
}
