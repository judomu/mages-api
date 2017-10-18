<?php

namespace Mages;


use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Game extends Model
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

  public function setGoalsTeam1(int $goals)
  {
    $this->goalsTeam1 = $goals;
  }

  public function setGoalsTeam2(int $goals)
  {
    $this->goalsTeam2 = $goals;
  }

  public function team1()
  {
    return $this->belongsTo('Mages\Team');
  }

  public function team2()
  {
    return $this->belongsTo('Mages\Team');
  }
}
