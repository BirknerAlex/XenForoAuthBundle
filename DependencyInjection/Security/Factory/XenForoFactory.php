<?php

namespace BirknerAlex\XenForoAuthBundle\DependencyInjection\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;

class XenForoFactory extends FormLoginFactory
{
    /**
     * @param ContainerBuilder $container
     * @param string $id
     * @param array $config
     * @param string $userProviderId
     * @return string
     */
    protected function createAuthProvider(ContainerBuilder $container, $id, $config, $userProviderId)
    {
        $providerId = 'security.authentication.provider.xenforo.' . $id;

        $container->setDefinition($providerId, new DefinitionDecorator('birkneralex.xenforo_auth.authentication.provider'))
            ->replaceArgument(0, new Reference($userProviderId));

        return $providerId;
    }

    /**
     * @return string
     */
    public function getPosition()
    {
        return 'form';
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'xenforo-login';
    }
}
