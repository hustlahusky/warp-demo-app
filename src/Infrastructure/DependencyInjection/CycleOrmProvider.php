<?php

declare(strict_types=1);

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Service\Bootstrap\CycleOrmWarmBootstrap;
use Cycle\Database;
use Cycle\ORM;
use Cycle\Schema;
use Psr\Log\LoggerInterface;
use Spiral\Core\FactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\VarExporter\VarExporter;
use Warp\Bridge\Cycle\Collection\Relation\SchemaExtra;
use Warp\Bridge\Cycle\Collection\Warp\WarpCollectionFactory;
use Warp\Bridge\Cycle\CycleEntityManager;
use Warp\Bridge\Cycle\EntityReferenceFactory;
use Warp\Bridge\Cycle\Factory\OrmFactory;
use Warp\Bridge\Cycle\Factory\SpiralFactory;
use Warp\Bridge\Cycle\Mapper\MapperPluginInterface;
use Warp\Bridge\Cycle\Mapper\Plugin\BelongsToLink\BelongsToLinkPlugin;
use Warp\Bridge\Cycle\Mapper\Plugin\Blame\BlamePlugin;
use Warp\Bridge\Cycle\Mapper\Plugin\DispatcherMapperPlugin;
use Warp\Bridge\Cycle\Mapper\Plugin\EntityEvents\EntityEventsPlugin;
use Warp\Bridge\Cycle\Mapper\Plugin\ForceEntityReference\ForceEntityReferencePlugin;
use Warp\Bridge\Cycle\Mapper\Plugin\GroupData\GroupDataPlugin;
use Warp\Bridge\Cycle\Schema\ArraySchemaRegistryFactory;
use Warp\Collection\Collection;
use Warp\Container\FactoryAggregateInterface;
use Warp\Container\InstanceOfAliasContainer;
use Warp\Container\ServiceProvider\AbstractServiceProvider;
use Warp\DataSource\Blame\BlameActorProviderInterface;
use Warp\DataSource\EntityPersisterAggregateInterface;
use Warp\DataSource\EntityReaderAggregateInterface;

final class CycleOrmProvider extends AbstractServiceProvider
{
    public function provides(): array
    {
        return [
            // ORM
            FactoryInterface::class,
            Database\DatabaseProviderInterface::class,
            Database\DatabaseManager::class,
            ORM\ORMInterface::class,
            ORM\ORM::class,
            Schema\Registry::class,
            // Entity manager
            EntityReaderAggregateInterface::class,
            EntityPersisterAggregateInterface::class,
            CycleEntityManager::class,
            // Mapper Plugin
            MapperPluginInterface::class,
            DispatcherMapperPlugin::class,
            // Bootstrap
            CycleOrmWarmBootstrap::class,
            DefinitionTag::BOOTSTRAP,
        ];
    }

    public function register(): void
    {
        // ORM
        $this->getContainer()->define(FactoryInterface::class, [$this, 'makeFactory'], true);

        $this->getContainer()->define(Database\DatabaseProviderInterface::class, Database\DatabaseManager::class);
        $this->getContainer()->define(Database\DatabaseManager::class, [$this, 'makeDatabaseLayer'], true);

        $this->getContainer()->define(Schema\Registry::class, [$this, 'makeSchemaRegistry'], true);

        $this->getContainer()->define(ORM\ORMInterface::class, ORM\ORM::class);
        $this->getContainer()->define(ORM\ORM::class, [$this, 'makeORM'], true);

        // Entity manager
        $this->getContainer()->define(EntityReaderAggregateInterface::class, CycleEntityManager::class);
        $this->getContainer()->define(EntityPersisterAggregateInterface::class, CycleEntityManager::class);
        $this->getContainer()->define(CycleEntityManager::class, shared: true);

        // Mapper Plugin
        $this->getContainer()->define(MapperPluginInterface::class, DispatcherMapperPlugin::class);
        $this->getContainer()->define(DispatcherMapperPlugin::class, [$this, 'makeMapperPlugin'], true);

        // Bootstrap
        $this->getContainer()->define(CycleOrmWarmBootstrap::class)->addTag(DefinitionTag::BOOTSTRAP);
    }

    public function makeMapperPlugin(): DispatcherMapperPlugin
    {
        $eventDispatcher = new EventDispatcher();
        $plugin = new DispatcherMapperPlugin($eventDispatcher);

        $factory = InstanceOfAliasContainer::wrap($this->getContainer())->get(FactoryAggregateInterface::class);

        // Provides fields grouping by naming strategy
        $eventDispatcher->addSubscriber($factory->make(GroupDataPlugin::class));
        // Force using of entity reference for relations
        $eventDispatcher->addSubscriber($factory->make(ForceEntityReferencePlugin::class));
        // Force linking belongs/refers to relations while persisting entity only
        $eventDispatcher->addSubscriber($factory->make(BelongsToLinkPlugin::class));
        // Dispatch events from entities after persist
        $eventDispatcher->addSubscriber($factory->make(EntityEventsPlugin::class));

        // Mark entity created/updated by
        if ($this->getContainer()->has(BlameActorProviderInterface::class)) {
            $eventDispatcher->addSubscriber($factory->make(BlamePlugin::class));
        }

        return $plugin;
    }

    public function makeFactory(): FactoryInterface
    {
        return new SpiralFactory(
            InstanceOfAliasContainer::wrap($this->getContainer())->get(FactoryAggregateInterface::class)
        );
    }

    public function makeDatabaseLayer(): Database\DatabaseManager
    {
        $config = $this->getContainer()->get(ConfigProvider::ID);

        $dbal = new Database\DatabaseManager(new Database\Config\DatabaseConfig($config['database']));

        if ($this->getContainer()->has(LoggerInterface::class)) {
            $dbal->setLogger($this->getContainer()->get(LoggerInterface::class));
        }

        return $dbal;
    }

    public function makeSchemaRegistry(): Schema\Registry
    {
        $dbal = $this->getContainer()->get(Database\DatabaseProviderInterface::class);

        $factory = new ArraySchemaRegistryFactory($dbal);

        foreach ($this->findSchemaFiles() as $file) {
            $factory->loadEntity(require $file->getPathname());
        }

        $registry = $factory->make();

        /** @var Schema\GeneratorInterface[] $generators */
        $generators = [
            new Schema\Generator\ResetTables(),
            new Schema\Generator\GenerateRelations(),
            //            new Schema\Generator\ValidateEntities(),
            new Schema\Generator\RenderTables(),
            new Schema\Generator\RenderRelations(),
        ];
        foreach ($generators as $generator) {
            $registry = $generator->run($registry);
        }

        return $registry;
    }

    public function makeORM(): ORM\ORM
    {
        $dbal = $this->getContainer()->get(Database\DatabaseProviderInterface::class);

        // Using custom OrmFactory instead of default cycle orm factory, that auto enable enhanced to-many relationships
        $factory = new OrmFactory($dbal, null, $this->getContainer()->get(FactoryInterface::class));
        $factory = $factory->withDefaultSchemaClasses([
            SchemaExtra::COLLECTION_FACTORY => WarpCollectionFactory::class,
        ]);

        $schema = new ORM\Schema($this->loadCycleSchema());

        return (new ORM\ORM($factory, $schema))->withPromiseFactory(new EntityReferenceFactory());
    }

    /**
     * Cycle's schema builder do a lot of database queries and can takes up to seconds per request in some cases.
     * Usually schema builder used only for generating schema. So we call it only once and cache output.
     * We use lock so parallel requests would not do same job multiple times. Instead, them waits up to 5 seconds until
     * schema cache available or fail.
     * @return array<mixed>
     * @todo: This code can be prettier
     */
    private function loadCycleSchema(): array
    {
        $schemaFile = ROOT_DIR . '/resources/.cycle_schema.php';

        if (\is_file($schemaFile)) {
            $schemaFileInfo = new \SplFileInfo($schemaFile);

            if ($schemaFileInfo->getMTime() >= $this->getSchemaFilesMTime()) {
                return require $schemaFile;
            }
        }

        $lockFactory = $this->getContainer()->get(LockFactory::class);
        $lock = $lockFactory->createLock(__METHOD__);

        if ($lock->acquire()) {
            try {
                $registry = $this->getContainer()->get(Schema\Registry::class);
                $schema = (new Schema\Compiler())->compile($registry);

                (new Filesystem())->dumpFile($schemaFile, \sprintf(
                    <<<'EOT'
                    <?php

                    return %s;
                    EOT,
                    VarExporter::export($schema),
                ));

                return $schema;
            } finally {
                $lock->release();
            }
        }

        $slept = 0;
        while (5 > $slept) {
            if (\is_file($schemaFile)) {
                break;
            }

            \usleep(200000);
            $slept += 0.2;
        }

        if (!\is_file($schemaFile)) {
            throw new \RuntimeException('Cannot resolve ORM schema!');
        }

        return require $schemaFile;
    }

    private function findSchemaFiles(): Finder
    {
        return Finder::create()->files()->in(ROOT_DIR . '/resources/cycle')->name('*.php');
    }

    private function getSchemaFilesMTime(): int
    {
        $mtime = Collection::new($this->findSchemaFiles())
            ->map(static fn (\SplFileInfo $f) => $f->getMTime())
            ->max();

        return (int)$mtime;
    }
}
