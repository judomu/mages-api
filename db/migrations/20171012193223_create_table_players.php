<?php


use Phinx\Migration\AbstractMigration;

class CreateTablePlayers extends AbstractMigration
{
  public function change()
  {
    $table = $this->table('players', ['id' => false, 'primary_key' => 'id'])
      ->addColumn('id', 'string')
      ->addColumn('username', 'string')
      ->addColumn('created_at', 'string')
      ->addColumn('updated_at', 'string')
      ->create();
  }
}
