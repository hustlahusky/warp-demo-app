<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Laminas\ConfigAggregator\ConfigAggregator;
use PhpOption\Option;
use Symfony\Component\Filesystem\Filesystem;

final class ConfigFacade
{
    private string|null $cacheFile;

    private Filesystem $filesystem;

    /**
     * @var Option<ConfigAggregator>
     */
    private Option $config;

    /**
     * @param callable[] $providers
     * @param callable[] $postProcessors
     */
    public function __construct(array $providers = [], string|null $configFile = null, array $postProcessors = [])
    {
        $this->cacheFile = $configFile;
        $this->filesystem = new Filesystem();
        // @phpstan-ignore-next-line
        $this->config = Option::ensure(static fn () => new ConfigAggregator($providers, $configFile, $postProcessors));
    }

    /**
     * @return array<string,mixed>
     */
    public function getMergedConfig(): array
    {
        return $this->config->get()->getMergedConfig();
    }

    public function refresh(): void
    {
        if (null === $this->cacheFile) {
            return;
        }

        $this->filesystem->remove($this->cacheFile);
    }
}
