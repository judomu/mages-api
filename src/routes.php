<?php
// Routes

use Slim\Http\Request;
use Slim\Http\Response;
use Sirius\Upload\Handler as UploadHandler;

$app->post('/api/player', function (Request $request, Response $response, $args) use ($app) {
  $data = $request->getParsedBody();

  $newPlayer = $this->playerApplicationService->createPlayer($data['username'], $data['password'],
    $data['alias'], $data['fullname']);

  return $response->withJson($newPlayer);
});

$app->post('/api/player/{id}/avatar', function (Request $request, Response $response, $args) use ($app) {
  $uploadHandler = new UploadHandler($app->getContainer()->get('settings')['files']['folder_path']);
  $uploadHandler->addRule('image', ['allowed' => ['jpg', 'jpeg', 'png']],
    '{label} must be a valid image (jpg, jpeg, png)', 'Avatar');
  $uploadHandler->addRule('size', ['max' => '512K'],
    '{label} must have less than {max}', 'Avatar');
  $uploadHandler->addRule('imageratio', ['ratio' => '1:1'],
    '{label} must be a square image', 'Avatar');
  $uploadHandler->addRule('imagewidth', ['min' => 100, 'max' => 200],
    '{label} must be at least {min} pixels wide and max {max} wide', 'Avatar');
  $uploadHandler->addRule('imageheight', ['min' => 100, 'max' => 200],
    '{label} must be at least {min} pixels tall and max {max} tall', 'Avatar');

  $result = $uploadHandler->process($_FILES['avatar']);

  if ($result->isValid()) {
    echo $app->getContainer()->get('settings')['files']['folder'] . '/' . $result->name;
  } else {
    throw new InvalidArgumentException(implode(', ', $result->getMessages()));
  }
});

$app->get('/api/player/{id}', function (Request $request, Response $response, $args) {
  $resolvedPlayer = $this->playerApplicationService->getPlayer($args['id']);

  if ($resolvedPlayer == null) {
    return $response->withStatus(404);
  } else {
    return $response->withJson($resolvedPlayer);
  }
});

$app->post('/api/team', function (Request $request, Response $response, $args) {
  $players = $request->getParsedBody()['players'];
  return $response->withJson($this->teamApplicationService->createTeam($players));
});

$app->get('/api/team', function (Request $request, Response $response, $args) {
  if (($players = $request->getQueryParam('withPlayers')) != null) {
    $team = $this->teamApplicationService->getTeamByPlayers($players);

    if (sizeof($team) !== 1) return $response->withStatus(404);
    return $response->withJson($team);
  }

  return $response->withJson($this->teamApplicationService->getAllTeams($players));
});

$app->get('/api/team/{id}', function (Request $request, Response $response, $args) {
  $team = $this->teamApplicationService->getTeam($args['id']);

  if ($team === null) return $response->withStatus(404);
  return $response->withJson($team);
});

$app->post('/api/game', function (Request $request, Response $response, $args) {
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

$app->get('/api/game', function (Request $request, Response $response, $args) {
  if (($teamId = $request->getQueryParam('withTeam')) != null) {
    return $response->withJson($this->gameApplicationService->getGamesWithTeam($teamId));
  } else {
    return $response->withJson($this->gameApplicationService->getAllGames());
  }
});

$app->get('/api/game/{id}', function (Request $request, Response $response, $args) {
  $game = $this->gameApplicationService->getGame($args['id']);

  if ($game === null) return $response->withStatus(404);
  return $response->withJson($game);
});
