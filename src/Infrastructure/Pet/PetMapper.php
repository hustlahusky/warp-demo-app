<?php

declare(strict_types=1);

namespace App\Infrastructure\Pet;

use App\Domain\Pet\Pet;
use App\Domain\Pet\PetId;
use App\Domain\Pet\PetType;
use App\Domain\User\User;
use Cycle\ORM\ORMInterface;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\NamingStrategy\NamingStrategyEnabledInterface;
use Laminas\Hydrator\Strategy\StrategyEnabledInterface;
use Warp\Bridge\Cycle\Mapper\HydratorMapper;
use Warp\Bridge\Cycle\Mapper\MapperPluginInterface;
use Warp\Bridge\LaminasHydrator\Strategy\BlameStrategy;
use Warp\Bridge\LaminasHydrator\Strategy\DateValueStrategy;
use Warp\Bridge\LaminasHydrator\Strategy\ValueObjectStrategy;

/**
 * @see Pet
 */
final class PetMapper extends HydratorMapper
{
    public function __construct(ORMInterface $orm, string $role, ?MapperPluginInterface $plugin = null)
    {
        parent::__construct($orm, $role, null, $plugin);

        \assert($this->hydrator instanceof StrategyEnabledInterface
            && $this->hydrator instanceof NamingStrategyEnabledInterface);

        // Group fields
        $this->hydrator->setNamingStrategy(MapNamingStrategy::createFromHydrationMap([
            'createdAt' => 'blame.createdAt',
            'createdBy' => 'blame.createdBy',
            'updatedAt' => 'blame.updatedAt',
            'updatedBy' => 'blame.updatedBy',
        ]));

        $dateStrategy = new DateValueStrategy('Y-m-d H:i:s');
        $this->hydrator->addStrategy('id', new ValueObjectStrategy(PetId::class));
        $this->hydrator->addStrategy('type', new ValueObjectStrategy(PetType::class));
        $this->hydrator->addStrategy('birthdate', $dateStrategy);
        $this->hydrator->addStrategy('blame', new BlameStrategy(User::class, dateStrategy: $dateStrategy));
    }
}
