<?php


use Phinx\Migration\AbstractMigration;

class AddPlayerAvatar extends AbstractMigration
{
    public function change()
    {
      $this->table('players')
        ->addColumn('avatar', 'string', ['null' => true])
        ->update();
    }
}
