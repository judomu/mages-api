<?php


use Phinx\Migration\AbstractMigration;

class CreateTableTeams extends AbstractMigration
{
  public function change()
  {
    $table = $this->table('teams', ['id' => false, 'primary_key' => 'id'])
      ->addColumn('id', 'string')
      ->addColumn('created_at', 'string')
      ->addColumn('updated_at', 'string')
      ->create();

    $this->table('team_player', ['id' => false, 'primary_key' => ['team_id', 'player_id']])
      ->addColumn('team_id', 'string')
      ->addColumn('player_id', 'string')
      ->addForeignKey('team_id', 'teams', 'id')
      ->addForeignKey('player_id', 'players', 'id')
      ->create();
  }
}
