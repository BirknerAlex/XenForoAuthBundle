<?php

namespace BirknerAlex\XenForoAuthBundle\Security\Authentication\Provider;

use BirknerAlex\Xenforo\XenForoSDK;
use BirknerAlex\XenForoAuthBundle\Security\Authentication\Token\XenForoToken;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class XenForoProvider implements AuthenticationProviderInterface
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var XenForoSDK
     */
    private $xenforo;

    /**
     * @param UserProviderInterface $userProvider
     * @param XenForoSDK $xenforo
     */
    public function __construct(UserProviderInterface $userProvider, XenForoSDK $xenforo)
    {
        $this->userProvider = $userProvider;
        $this->xenforo = $xenforo;
    }

    public function authenticate(TokenInterface $token)
    {
        $user = $this->userProvider->loadUserByUsername($token->getUsername());

        if ($user
            && (
                $token->isAuthenticated()
                || $this->xenforo->isLoggedIn())) {
            $authenticatedToken = new XenForoToken($user->getRoles());
            $authenticatedToken->setUser($user);

            return $authenticatedToken;
        }

        throw new AuthenticationException('The XenForo authentication failed.');
    }

    /**
     * Checks whether this provider supports the given token.
     *
     * @param TokenInterface $token A TokenInterface instance
     *
     * @return Boolean true if the implementation supports the Token, false otherwise
     */
    public function supports(TokenInterface $token)
    {
        return $token instanceof UsernamePasswordToken || $token instanceof XenForoToken;
    }
}
