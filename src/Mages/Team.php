<?php
/**
 * Created by PhpStorm.
 * User: julmuell
 * Date: 17.09.17
 * Time: 18:14
 */

namespace Mages;


use Illuminate\Database\Eloquent\Model;
use Mages\Player;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Team extends Model implements \JsonSerializable
{
  public $incrementing = false;

  public $keyType = 'string';

  function __construct()
  {
    $this->id = Uuid::getFactory()->uuid4();
  }

  public function jsonSerialize()
  {
    return array_merge(parent::jsonSerialize(), ['games' => $this->games()->get()]);
  }

  public function games1()
  {
    return $this->hasMany('Mages\Game', 'team1_id');
  }

  public function games2()
  {
    return $this->hasMany('Mages\Game', 'team2_id');
  }

  public function games()
  {
    return $this->games1()->union($this->games2());
  }

  public function players()
  {
    return $this->belongsToMany('Mages\Player', 'team_player');
  }
}
