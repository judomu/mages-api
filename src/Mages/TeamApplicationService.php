<?php
/**
 * Created by PhpStorm.
 * User: julmuell
 * Date: 17.09.17
 * Time: 18:20
 */

namespace Mages;


use Illuminate\Database\Eloquent\Builder;
use Webmozart\Assert\Assert;

class TeamApplicationService
{
  private $containerService;

  private $playerApplicationService;

  public function __construct($cs)
  {
    $this->containerService = $cs;

    $this->playerApplicationService = $this->containerService->get('playerApplicationService');
  }

  public function createTeam(array $playerIds)
  {
    Assert::notEmpty($playerIds, 'Team without players?');

    $existingTeam = $this->getTeamByPlayers($playerIds);

    if (sizeof($existingTeam) == 1) {
      return $existingTeam[0];
    }

    $players = [];

    foreach ($playerIds as $playerId) {
      $players[] = $player = $this->playerApplicationService->getPlayer($playerId);
      Assert::notNull($player, "Player with id $playerId not found");
    }

    $team = new Team;
    $team->players()->attach($playerIds);

    $team->save();

    return $team;
  }

  public function getTeamByPlayers(array $playerIds)
  {
    return Team::query()->has('players', '=', sizeof($playerIds))
      ->whereHas('players', function (Builder $query) use ($playerIds) {
        $query->whereIn('id', $playerIds);
      })
      ->with('players')// resolve players relation
      ->get();
  }

  public function getTeam(string $id)
  {
    return Team::with('players')
      ->find($id);
  }

  public function getAllTeams()
  {
    return Team::query()->get();
  }
}
