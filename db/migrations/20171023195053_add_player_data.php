<?php


use Phinx\Migration\AbstractMigration;

class AddPlayerData extends AbstractMigration
{
    public function change()
    {
      $this->table('players')
        ->addColumn('password', 'string')
        ->addColumn('alias', 'string')
        ->addColumn('fullname', 'string')
        ->update();
    }
}
