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

    function flattenArray(array $array): array {
        $result = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                $result = array_merge($result, $this->flattenArray($item));
            } else {
                $result[] = $item;
            }
        }

        return $result;
    }

    public function allowsCallOfMethod($methodName)
    {
        return true;
    }
}
