<?php

declare(strict_types=1);

namespace App;

use App\Domain\Pet\Pet;
use App\Domain\Pet\PetId;
use App\Domain\User\UserId;
use App\Infrastructure\Pet\PetMapper;
use spaceonfire\Bridge\Cycle\Schema\EntityDto;
use spaceonfire\Bridge\Cycle\Schema\FieldDto;
use spaceonfire\Bridge\Cycle\Schema\RelationDto;
use Spiral\Database\Schema\AbstractColumn;

return [
    EntityDto::ROLE => PetId::ROLE,
    EntityDto::TABLE => 'pet',
    EntityDto::CLASS_NAME => Pet::class,
    EntityDto::MAPPER => PetMapper::class,
    EntityDto::FIELDS => [
        [
            FieldDto::NAME => 'id',
            FieldDto::COLUMN => 'id',
            FieldDto::TYPE => 'uuid',
            FieldDto::PRIMARY => true,
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => false,
            ],
        ],
        [
            FieldDto::NAME => 'name',
            FieldDto::COLUMN => 'name',
            FieldDto::TYPE => 'string(255)',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'type',
            FieldDto::COLUMN => 'type',
            FieldDto::TYPE => 'string(255)',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'birthdate',
            FieldDto::COLUMN => 'birthdate',
            FieldDto::TYPE => 'datetime',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'ownerId',
            FieldDto::COLUMN => 'owner_id',
            FieldDto::TYPE => 'uuid',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'createdAt',
            FieldDto::COLUMN => 'created_at',
            FieldDto::TYPE => 'datetime',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
                FieldDto::OPTION_DEFAULT => AbstractColumn::DATETIME_NOW,
            ],
        ],
        [
            FieldDto::NAME => 'updatedAt',
            FieldDto::COLUMN => 'updated_at',
            FieldDto::TYPE => 'datetime',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
                FieldDto::OPTION_DEFAULT => AbstractColumn::DATETIME_NOW,
            ],
        ],
        [
            FieldDto::NAME => 'createdById',
            FieldDto::COLUMN => 'created_by',
            FieldDto::TYPE => 'uuid',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'updatedById',
            FieldDto::COLUMN => 'updated_by',
            FieldDto::TYPE => 'uuid',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
    ],
    EntityDto::RELATIONS => [
        [
            RelationDto::NAME => 'owner',
            RelationDto::TARGET => UserId::ROLE,
            RelationDto::TYPE => RelationDto::TYPE_BELONGS_TO,
            RelationDto::OPTIONS => [
                RelationDto::OPTION_NULLABLE => true,
                RelationDto::OPTION_INNER_KEY => 'ownerId',
                RelationDto::OPTION_OUTER_KEY => 'id',
            ],
        ],
        [
            RelationDto::NAME => 'createdBy',
            RelationDto::TARGET => UserId::ROLE,
            RelationDto::TYPE => RelationDto::TYPE_REFERS_TO,
            RelationDto::OPTIONS => [
                RelationDto::OPTION_NULLABLE => true,
                RelationDto::OPTION_INNER_KEY => 'createdById',
                RelationDto::OPTION_OUTER_KEY => 'id',
            ],
        ],
        [
            RelationDto::NAME => 'updatedBy',
            RelationDto::TARGET => UserId::ROLE,
            RelationDto::TYPE => RelationDto::TYPE_REFERS_TO,
            RelationDto::OPTIONS => [
                RelationDto::OPTION_NULLABLE => true,
                RelationDto::OPTION_INNER_KEY => 'updatedById',
                RelationDto::OPTION_OUTER_KEY => 'id',
            ],
        ],
    ],
];
