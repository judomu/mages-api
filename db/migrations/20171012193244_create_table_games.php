<?php


use Phinx\Migration\AbstractMigration;

class CreateTableGames extends AbstractMigration
{
  public function change()
  {
    $this->table('games', ['id' => false, 'primary_key' => 'id'])
      ->addColumn('id', 'string')
      ->addColumn('goalsTeam1', 'integer')
      ->addColumn('goalsTeam2', 'integer')
      ->addColumn('team1_id', 'string')
      ->addForeignKey('team1_id', 'teams', 'id')
      ->addColumn('team2_id', 'string')
      ->addForeignKey('team2_id', 'teams', 'id')
      ->addColumn('created_at', 'string')
      ->addColumn('updated_at', 'string')
      ->create();
  }
}
