<?php
// Routes

use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/api/players', function (Request $request, Response $response, $args) use ($app) {
  $data = $request->getParsedBody();

  $newPlayer = $this->playerApplicationService->createPlayer($data['username'], $data['password'],
    $data['alias'], $data['fullname']);

  return $response->withJson($newPlayer);
});

$app->get('/api/players', function (Request $request, Response $response, $args) {
  $perPage = $request->getQueryParam('perPage') ?: 5;
  $page = $request->getQueryParam('page') ?: 1;

  return $response->withJson($this->playerApplicationService->getPlayers($perPage, $page));
});


$app->get('/api/players/{id}', function (Request $request, Response $response, $args) {
  $resolvedPlayer = $this->playerApplicationService->getPlayer($args['id']);

  if ($resolvedPlayer == null) {
    return $response->withStatus(404);
  } else {
    return $response->withJson($resolvedPlayer);
  }
});

$app->post('/api/players/{id}/avatar', function (Request $request, Response $response, $args) {
  $player = $this->playerApplicationService->attachAvatar($args['id'], $_FILES['avatar']);

  return $response->withJson($player);
});

$app->post('/api/teams', function (Request $request, Response $response, $args) {
  $players = $request->getParsedBody()['players'];
  return $response->withJson($this->teamApplicationService->createTeam($players));
});

$app->get('/api/teams', function (Request $request, Response $response, $args) {
  if (($players = $request->getQueryParam('withPlayers')) != null) {
    $team = $this->teamApplicationService->getTeamByPlayers($players);

    if (sizeof($team) !== 1) return $response->withStatus(404);
    return $response->withJson($team);
  }

  return $response->withJson($this->teamApplicationService->getAllTeams($players));
});

$app->get('/api/teams/{id}', function (Request $request, Response $response, $args) {
  $team = $this->teamApplicationService->getTeam($args['id']);

  if ($team === null) return $response->withStatus(404);
  return $response->withJson($team);
});

$app->post('/api/games', function (Request $request, Response $response, $args) {
  $goalsTeam1 = $request->getParsedBody()['goalsTeam1'];
  $goalsTeam2 = $request->getParsedBody()['goalsTeam2'];

  if (($team1 = $request->getParsedBody()['team1']) != null &&
    ($team2 = $request->getParsedBody()['team2']) != null) {

    return $response->withJson($this->gameApplicationService->createGameByTeams($team1, $team2, $goalsTeam1, $goalsTeam2));
  } else if (($playersTeam1 = $request->getParsedBody()['playersTeam1']) != null &&
    ($playersTeam2 = $request->getParsedBody()['playersTeam2']) != null) {

    return $response->withJson($this->gameApplicationService->createGameByPlayers($playersTeam1, $playersTeam2, $goalsTeam1, $goalsTeam2));
  }
});

$app->get('/api/games', function (Request $request, Response $response, $args) {
  if (($teamId = $request->getQueryParam('withTeam')) != null) {
    return $response->withJson($this->gameApplicationService->getGamesWithTeam($teamId));
  } else {
    return $response->withJson($this->gameApplicationService->getAllGames());
  }
});

$app->get('/api/games/{id}', function (Request $request, Response $response, $args) {
  $game = $this->gameApplicationService->getGame($args['id']);

  if ($game === null) return $response->withStatus(404);
  return $response->withJson($game);
});
