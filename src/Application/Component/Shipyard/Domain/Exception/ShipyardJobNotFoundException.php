<?php

declare(strict_types=1);

namespace TheGame\Application\Component\Shipyard\Domain\Exception;

use DomainException;
use TheGame\Application\Component\Shipyard\Domain\JobIdInterface;

final class ShipyardJobNotFoundException extends DomainException
{
    public function __construct(
        JobIdInterface $jobId,
    ) {
        $message = sprintf('Shipyard job %s has not been found', $jobId->getUuid());

        parent::__construct($message);
    }
}
