<?php

declare(strict_types=1);

namespace App\Infrastructure\User;

use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use Cycle\ORM\ORMInterface;
use Laminas\Hydrator\NamingStrategy\MapNamingStrategy;
use Laminas\Hydrator\NamingStrategy\NamingStrategyEnabledInterface;
use Laminas\Hydrator\Strategy\ClosureStrategy;
use Laminas\Hydrator\Strategy\StrategyEnabledInterface;
use spaceonfire\Bridge\Cycle\Mapper\HydratorMapper;
use spaceonfire\Bridge\Cycle\Mapper\MapperPluginInterface;
use spaceonfire\Bridge\LaminasHydrator\Strategy\BlameStrategy;
use spaceonfire\Bridge\LaminasHydrator\Strategy\DateValueStrategy;
use spaceonfire\Bridge\LaminasHydrator\Strategy\ValueObjectStrategy;
use spaceonfire\ValueObject\EmailValue;

/**
 * @see User
 */
final class UserMapper extends HydratorMapper
{
    public function __construct(ORMInterface $orm, string $role, ?MapperPluginInterface $plugin = null)
    {
        parent::__construct($orm, $role, null, $plugin);

        \assert($this->hydrator instanceof StrategyEnabledInterface
            && $this->hydrator instanceof NamingStrategyEnabledInterface);

        $this->hydrator->setNamingStrategy(MapNamingStrategy::createFromHydrationMap([
            'nameFirst' => 'name.firstName',
            'nameLast' => 'name.lastName',
            'createdAt' => 'blame.createdAt',
            'createdBy' => 'blame.createdBy',
            'updatedAt' => 'blame.updatedAt',
            'updatedBy' => 'blame.updatedBy',
        ]));

        $this->hydrator->addStrategy('id', new ValueObjectStrategy(UserId::class));
        $this->hydrator->addStrategy('email', new ValueObjectStrategy(EmailValue::class));
        $this->hydrator->addStrategy('name', new ClosureStrategy(
            static fn (UserName $name) => [
                'firstName' => $name->getFirstName(),
                'lastName' => $name->getLastName(),
            ],
            static fn (array $data) => new UserName($data['firstName'], $data['lastName']),
        ));
        $this->hydrator->addStrategy(
            'blame',
            new BlameStrategy(User::class, dateStrategy: new DateValueStrategy('Y-m-d H:i:s')),
        );
    }
}
