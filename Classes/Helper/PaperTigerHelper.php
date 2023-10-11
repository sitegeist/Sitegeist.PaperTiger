<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\Helper;

use Neos\Eel\ProtectedContextAwareInterface;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Cryptography\HashService;

class PaperTigerHelper implements ProtectedContextAwareInterface
{
    #[Flow\Inject]
    protected HashService $hashService;

    public function timestampWithHmac(): string
    {
        return $this->hashService->appendHmac((string) time());
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
