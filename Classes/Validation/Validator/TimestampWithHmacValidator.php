<?php

declare(strict_types=1);

namespace Sitegeist\PaperTiger\Validation\Validator;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Cryptography\HashService;
use Neos\Flow\Security\Exception\InvalidArgumentForHashGenerationException;
use Neos\Flow\Security\Exception\InvalidHashException;
use Neos\Flow\Validation\Validator\AbstractValidator;

class TimestampWithHmacValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    /**
     * @var array<string, mixed>
     */
    protected $supportedOptions = [
        'minimumAge' => [10, 'Minimum age for the hmacked timestamp', 'integer'],
        'maximumAge' => [86400, 'Maximum age for the hmacked timestamp', 'integer']
    ];

    #[Flow\Inject]
    protected HashService $hashService;

    protected function isValid($value)
    {
        try {
            $timestamp = $this->hashService->validateAndStripHmac($value);
            $age = time() - (int)$timestamp;
          if ($age > $this->options['maximumAge']) {
            $this->addError("Timestamp is too old", 1696525723);
        } else if ($age < $this->options['minimumAge']) {
            $this->addError("Timestamp is too young", 1696525723);
        }
        } catch (InvalidArgumentForHashGenerationException | InvalidHashException) {
            $this->addError("Hmac did not match", 1696525723);
        }
    }
}
