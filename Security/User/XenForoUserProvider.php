<?php

namespace BirknerAlex\XenForoAuthBundle\Security\User;

use BirknerAlex\Xenforo\XenForoSDK;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class XenForoUserProvider implements UserProviderInterface
{
    /**
     * @var XenForoSDK
     */
    private $xenforo;

    /**
     * @var Session
     */
    private $session;

    public function __construct(XenForoSDK $xenforo, Session $session)
    {
        $this->xenforo = $xenforo;
        $this->session = $session;
    }

    /**
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     *
     */
    public function loadUserByUsername($username)
    {
        $sessionKey = 'birkneralex_xenforo_auth.user';

        if ($this->session->has($sessionKey)) {
            return $this->session->get($sessionKey);
        }

        try {
            if (!$this->xenforo->isLoggedIn()) {
                throw new UsernameNotFoundException();
            }

            $user = User::fromUser($this->xenforo->getUser(1));

            $this->session->set($sessionKey, $user);

            return $user;
        } catch (UsernameNotFoundException $e) {
            throw new UsernameNotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user))) {
            throw new UnsupportedUserException();
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @param string $class
     *
     * @return Boolean
     */
    public function supportsClass($class)
    {
        return $class == 'BirknerAlex\XenForoAuthBundle\Security\User\User';
    }
}
