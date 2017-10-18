<?php
/**
 * Created by PhpStorm.
 * User: julmuell
 * Date: 10.10.17
 * Time: 07:20
 */

namespace Mages;


use Webmozart\Assert\Assert;

class GameApplicationService
{
  private $containerService;

  public function __construct($cs)
  {
    $this->containerService = $cs;
  }

  private function createGame(Team $team1, int $goalsTeam1, Team $team2, int $goalsTeam2)
  {
    $game = new Game;
    $game->setGoalsTeam1($goalsTeam1);
    $game->setGoalsTeam2($goalsTeam2);
    $game->team1()->associate($team1);
    $game->team2()->associate($team2);

    $game->save();

    return $game;
  }

  public function createGameByPlayers(array $playerIdsTeam1, array $playerIdsTeam2, int $goalsTeam1, int $goalsTeam2)
  {
    $teamApplicationService = $this->containerService->get('teamApplicationService');
    $team1 = $teamApplicationService->createTeam($playerIdsTeam1);
    $team2 = $teamApplicationService->createTeam($playerIdsTeam2);

    return $this->createGame($team1, $goalsTeam1, $team2, $goalsTeam2);
  }

  public function createGameByTeams(string $team1Id, string $team2Id, int $goalsTeam1, int $goalsTeam2)
  {
    $teamApplicationService = $this->containerService->get('teamApplicationService');
    $team1 = $teamApplicationService->getTeam($team1Id);
    $team2 = $teamApplicationService->getTeam($team2Id);

    return $this->createGame($team1, $goalsTeam1, $team2, $goalsTeam2);
  }

  public function getGame(string $id)
  {
    return Game::query()->with(['team1', 'team2'])->with('team1.players')->find($id);
  }

  public function getAllGames()
  {
    return Game::query()->with(['team1', 'team2'])->orderBy('updated_at', 'desc')->get();
  }

  public function getGamesWithTeam(string $teamId)
  {
    return Game::query()->where('team1_id', $teamId)->orWhere('team2_id', $teamId)->get();
  }
}
