<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Exception;

use DomainException;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;

final class CantCancelCurrentlyTakenJobException extends DomainException
{
    public function __construct(
        JobIdInterface $jobId,
    ) {
        $message = sprintf('Can\'t cancel already taken job %s', $jobId->getUuid());

        parent::__construct($message);
    }
}
