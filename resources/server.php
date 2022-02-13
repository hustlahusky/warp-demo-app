<?php

declare(strict_types=1);

namespace App;

use App\Infrastructure\Http\DefaultWorker;
use App\Infrastructure\Kernel;
use Slim\Psr7\Factory\ServerRequestFactory;

/** @var Kernel $kernel */
$kernel = (require __DIR__ . '/kernel.php')();

$request = ServerRequestFactory::createFromGlobals();

$worker = DefaultWorker::new($kernel->getContainer());
$worker->boot();

try {
    $worker->respond($worker->handle($request));
} finally {
    $worker->reset();
}
