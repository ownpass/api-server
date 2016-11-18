<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/api-server for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/api-server/master/LICENSE MIT
 */

namespace OwnPassApplication\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ControlPanelUrl extends AbstractHelper
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function __invoke()
    {
        return $this->url;
    }
}
