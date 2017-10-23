<?php

namespace Mages;


use Illuminate\Database\Query\Builder;
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
    if(sizeof(Player::query()->where('username', $username)->get()) != 0){
      throw new \InvalidArgumentException('User already exists');
    }

    $player = Player::createPlayer($username, $password, $alias, $fullname);

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
