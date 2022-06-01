<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Database\Injection\Fragment;
use Cycle\Migrations\Migration;

class OrmDefault1e9cdef871110defdd5f845952eb58f1 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('user')
            ->addColumn('id', 'uuid', [
                'nullable' => false,
                'default' => null,
            ])
            ->addColumn('email', 'string', [
                'nullable' => false,
                'default' => null,
                'size' => 255,
            ])
            ->addColumn('name_first', 'string', [
                'nullable' => true,
                'default' => null,
                'size' => 255,
            ])
            ->addColumn('name_last', 'string', [
                'nullable' => true,
                'default' => null,
                'size' => 255,
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
            ->addIndex(['email'], [
                'name' => 'user_email_index',
                'unique' => false,
            ])
            ->addIndex(['created_at'], [
                'name' => 'user_created_at_index',
                'unique' => false,
            ])
            ->addIndex(['updated_at'], [
                'name' => 'user_updated_at_index',
                'unique' => false,
            ])
            ->addIndex(['created_by'], [
                'name' => 'user_created_by_index',
                'unique' => false,
            ])
            ->addIndex(['updated_by'], [
                'name' => 'user_updated_by_index',
                'unique' => false,
            ])
            ->addForeignKey(['created_by'], 'user', ['id'], [
                'name' => 'user_created_by_fk',
                'delete' => 'SET DEFAULT',
                'update' => 'NO ACTION',
            ])
            ->addForeignKey(['updated_by'], 'user', ['id'], [
                'name' => 'user_updated_by_fk',
                'delete' => 'SET DEFAULT',
                'update' => 'NO ACTION',
            ])
            ->create();
    }

    public function down(): void
    {
        $this->table('user')->drop();
    }
}
