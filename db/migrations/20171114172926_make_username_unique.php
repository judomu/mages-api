<?php


use Phinx\Migration\AbstractMigration;

class MakeUsernameUnique extends AbstractMigration
{
    public function change()
    {
      $this->table('players')
        ->addIndex(['username'], [
          'unique' => true,
          'name' => 'idx_players_username'
        ])
        ->update();
    }
}
