<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Database\Injection\Fragment;
use Cycle\Migrations\Migration;

class OrmDefault2e51c45ed5d7eaf0f6d24303a7548b79 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('pet')
            ->addColumn('id', 'uuid', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('name', 'string', [
                'nullable' => false,
                'default' => null,
                'size' => 255,
            ])
            ->addColumn('type', 'string', [
                'nullable' => false,
                'default' => null,
                'size' => 255,
            ])
            ->addColumn('birthdate', 'timestamp', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('owner_id', 'uuid', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('created_at', 'timestamp', [
                'nullable' => false,
                'default' => new Fragment('now()'),
            ])
            ->addColumn('updated_at', 'timestamp', [
                'nullable' => false,
                'default' => new Fragment('now()'),
            ])
            ->addColumn('created_by', 'uuid', [
                'nullable' => true,
                'default' => null,
            ])
            ->addColumn('updated_by', 'uuid', [
                'nullable' => true,
                'default' => null,
            ])
            ->setPrimaryKeys(['id'])
            ->addIndex(['name'], [
                'name' => 'pet_name_index',
                'unique' => false,
            ])
            ->addIndex(['type'], [
                'name' => 'pet_type_index',
                'unique' => false,
            ])
            ->addIndex(['birthdate'], [
                'name' => 'pet_birthdate_index',
                'unique' => false,
            ])
            ->addIndex(['owner_id'], [
                'name' => 'pet_owner_id_index',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'pet_created_at_index',
                'unique' => false,
            ])
            ->addIndex(['updated_at'], [
                'name' => 'pet_updated_at_index',
                'unique' => false,
            ])
            ->addIndex(['created_by'], [
                'name' => 'pet_created_by_index',
                'unique' => false,
            ])
            ->addIndex(['updated_by'], [
                'name' => 'pet_updated_by_index',
                'unique' => false,
            ])
            ->addForeignKey(['owner_id'], 'user', ['id'], [
                'name' => 'pet_owner_id_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey(['created_by'], 'user', ['id'], [
                'name' => 'pet_created_by_fk',
                'delete' => 'SET DEFAULT',
                'update' => 'NO ACTION',
            ])
            ->addForeignKey(['updated_by'], 'user', ['id'], [
                'name' => 'pet_updated_by_fk',
                'delete' => 'SET DEFAULT',
                'update' => 'NO ACTION',
            ])
            ->create();
    }

    public function down(): void
    {
        $this->table('pet')->drop();
    }
}
