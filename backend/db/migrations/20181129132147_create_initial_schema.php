<?php


use Phinx\Migration\AbstractMigration;

class CreateInitialSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this
            ->table('analysis')
            ->addColumn('url', 'string', [ 'limit' => 255 ])
            ->addColumn('started_at', 'datetime')
            ->addColumn('finished_at', 'datetime')
            ->create()
            ;
        
        $this
            ->table('analysis_detail')
            ->addColumn('analysis_id', 'integer')
            ->addColumn('analyzer', 'string')
            ->addColumn('flags', 'integer')
            ->addColumn('score', 'integer')
            ->addColumn('message', 'text', [ 'limit' => 65535 ])
            ->addColumn('started_at', 'datetime')
            ->addColumn('finished_at', 'datetime')
            ->addForeignKey('analysis_id', 'analysis', 'id', [
                'update' => 'CASCADE',
                'delete' => 'CASCADE'
            ])
            ->create()
        ;
    }
}
