<?php

declare(strict_types=1);

namespace App;

use App\Domain\Pet\PetId;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Infrastructure\User\UserMapper;
use spaceonfire\Bridge\Cycle\Schema\EntityDto;
use spaceonfire\Bridge\Cycle\Schema\FieldDto;
use spaceonfire\Bridge\Cycle\Schema\RelationDto;
use Spiral\Database\Schema\AbstractColumn;

return [
    EntityDto::ROLE => UserId::ROLE,
    EntityDto::TABLE => 'user',
    EntityDto::CLASS_NAME => User::class,
    EntityDto::MAPPER => UserMapper::class,
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
            FieldDto::NAME => 'email',
            FieldDto::COLUMN => 'email',
            FieldDto::TYPE => 'string(255)',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => false,
            ],
        ],
        [
            FieldDto::NAME => 'nameFirst',
            FieldDto::COLUMN => 'name_first',
            FieldDto::TYPE => 'string(255)',
            FieldDto::OPTIONS => [
                FieldDto::OPTION_NULLABLE => true,
            ],
        ],
        [
            FieldDto::NAME => 'nameLast',
            FieldDto::COLUMN => 'name_last',
            FieldDto::TYPE => 'string(255)',
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
            RelationDto::NAME => 'pets',
            RelationDto::TARGET => PetId::ROLE,
            RelationDto::TYPE => RelationDto::TYPE_HAS_MANY,
            RelationDto::OPTIONS => [
                RelationDto::OPTION_NULLABLE => false,
                RelationDto::OPTION_INNER_KEY => 'id',
                RelationDto::OPTION_OUTER_KEY => 'ownerId',
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
