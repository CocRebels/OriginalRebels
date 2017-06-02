<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 2/27/17
 * Time: 2:46 PM
 */

namespace AppBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Role\RoleHierarchy;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;


class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $router;

    private $encoder;

    private $session;

    private $token;


    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder, ContainerInterface $container)
    {
        $this->router = $router;
        $this->encoder = $encoder;
        $this->session = $container->get('session');
        $this->token = $container->get('security.token_storage');
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login_check') {

            return;
        }

        $email = $request->request->get('_email');
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $password = $request->request->get('_password');

        return [
            'email' => $email,
            'password' => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $email = $credentials['email'];

        return $userProvider->loadUserByUsername($email);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];
        if ($this->encoder->isPasswordValid($user, $plainPassword)) {
            return true;
        }

        throw new BadCredentialsException();
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $userRoles = $token->getRoles();
        $userRole = $userRoles[0]->getRole();
        if ($userRole == 'ROLE_USER_NOT_VERIFIED') {
            $this->token->setToken(null);
            $this->session->invalidate();
            $this->session->getFlashBag()
                ->add('notice', 'You need to verify your email in order to log in!');
            return  new RedirectResponse($this->router->generate('login'));
        }

        $url = $this->router->generate('user_main_area');

        return new RedirectResponse($url);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        $url = $this->router->generate('login');

        return new RedirectResponse($url);
    }

    public function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    public function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('homepage');
    }

}