<?php

namespace Mages;


use Mages\Player;
use Ramsey\Uuid\Uuid;

class PlayerApplicationService
{
  private $containerService;

  public function __construct($cs)
  {
    $this->containerService = $cs;
  }

  public function createPlayer(string $username, string $password, string $alias, string $fullname)
  {
    $player = new Player($username, $password, $alias, $fullname);

    $player->save();

    return $player;
  }

  public function getPlayer($id)
  {
    return Player::query()
      ->with('teams')
      ->with('teams.players')
      ->find($id);
  }
}
