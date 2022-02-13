<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

abstract class DefinitionTag extends \spaceonfire\Container\Factory\DefinitionTag
{
    public const BOOTSTRAP = 'kernel.bootstrap';

    public const RESET = 'kernel.reset';

    public const EVENT_SUBSCRIBER = 'event_dispatcher.subscriber';

    public const MONOLOG_PROCESSOR = 'monolog.processor';
}
