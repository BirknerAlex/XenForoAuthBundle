<?php

namespace BirknerAlex\XenForoAuthBundle;

use BirknerAlex\XenForoAuthBundle\DependencyInjection\Security\Factory\XenforoFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BirknerAlexXenForoAuthBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new XenforoFactory());
    }
}
