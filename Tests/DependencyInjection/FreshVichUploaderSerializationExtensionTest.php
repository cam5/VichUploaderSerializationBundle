<?php
/*
 * This file is part of the FreshVichUploaderSerializationBundle
 *
 * (c) Artem Henvald <genvaldartem@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fresh\VichUploaderSerializationBundle\Tests\DependencyInjection;

use Fresh\VichUploaderSerializationBundle\DependencyInjection\FreshVichUploaderSerializationExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * FreshVichUploaderSerializationExtensionTest.
 *
 * @author Artem Henvald <genvaldartem@gmail.com>
 */
class FreshVichUploaderSerializationExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var FreshVichUploaderSerializationExtension */
    private $extension;

    /** @var ContainerBuilder */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->extension = new FreshVichUploaderSerializationExtension();
        $this->container = new ContainerBuilder();
        $this->container->registerExtension($this->extension);
    }

    public function testLoadExtension()
    {
        // Add some dummy required services
        $this->container->set('vich_uploader.storage', new \stdClass());
        $this->container->set('router.request_context', new \stdClass());
        $this->container->set('annotations.cached_reader', new \stdClass());
        $this->container->set('logger', new \stdClass());
        $this->container->set('property_accessor', new \stdClass());

        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertFalse($this->container->has('vich_uploader_serialization.jms_serializer.subscriber')); // because private
    }
}
