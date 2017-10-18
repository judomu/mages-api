<?php

namespace Mages;


use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Player extends Model
{
  // private $id;

  // private $username;

  public $incrementing = false;

  public $keyType = 'string';


  function __construct()
  {
    $this->id = Uuid::getFactory()->uuid4();
  }

  public function getId()
  {
    return $this->id;
  }

  public function setUsername($username)
  {
    $this->username = $username;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function equals(Player $player)
  {
    return $this->getId() === $player->getId();
  }

  public function teams()
  {
    return $this->belongsToMany('Mages\Team', 'team_player');
  }
}
