<?php

declare(strict_types=1);

namespace App\Interfaces\Security\Guard;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\TokenExtractorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\AccessMapInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class OnlySecuredJWTAuthenticator extends JWTAuthenticator
{
    public function __construct(
        JWTTokenManagerInterface $jwtManager,
        EventDispatcherInterface $eventDispatcher,
        TokenExtractorInterface $tokenExtractor,
        UserProviderInterface $userProvider,
        private readonly AccessMapInterface $accessMap,
    ) {
        parent::__construct($jwtManager, $eventDispatcher, $tokenExtractor, $userProvider);
    }

    public function supports(Request $request): bool
    {
        [$attributes] = $this->accessMap->getPatterns($request);

        if ($attributes === null) {
            $attributes = [];
        }

        return parent::supports($request)
            && !in_array(AuthenticatedVoter::PUBLIC_ACCESS, $attributes, true);
    }

    public function authenticate(Request $request): Passport
    {
        return $this->doAuthenticate($request);
    }
}
