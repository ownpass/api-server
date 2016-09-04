<?php
/**
 * This file is part of OwnPass. (https://github.com/ownpass/)
 *
 * @link https://github.com/ownpass/ownpass for the canonical source repository
 * @copyright Copyright (c) 2016-2017 OwnPass. (https://github.com/ownpass/)
 * @license https://raw.githubusercontent.com/ownpass/ownpass/master/LICENSE MIT
 */

namespace OwnPassApplicationTest\Controller;

use OwnPassApplication\Controller\IndexController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
 * Name is intentional, to force it to run last; this was necessary as,
 * once the Admin module is loaded, the controller is able to find it and
 * will attempt the redirect.
 */
class ZZIndexDevModeTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [
            'modules' => [
                'ZF\Apigility\Admin',
                'ZF\Apigility\Admin\Ui',
            ],
            'module_listener_options' => [
                'config_cache_enabled' => false,
                'config_glob_paths' => [
                    __DIR__ . '/../../../../config/autoload/{,*.}{global,local}.php',
                    __DIR__ . '/../../../../config/autoload/{,*.}{global,local}-development.php',
                ],
            ]
        ];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    /**
     * @covers OwnPassApplication\Controller\Index::indexAction
     */
    public function testApiActionRedirectsToAdminUi()
    {
        $this->dispatch('/api', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectRegex('#/ui$#');
    }
}
