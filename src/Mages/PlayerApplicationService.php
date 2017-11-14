<?php

namespace Mages;


use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Mages\Player;
use Ramsey\Uuid\Uuid;
use Sirius\Upload\Handler as UploadHandler;

class PlayerApplicationService
{
  private $containerService;

  public function __construct($cs)
  {
    $this->containerService = $cs;
  }

  public function createPlayer(string $username, string $password, string $alias, string $fullname)
  {
    if (sizeof(Player::query()->where('username', $username)->get()) != 0) {
      throw new \InvalidArgumentException('User already exists');
    }

    $player = Player::createPlayer($username, $password, $alias, $fullname);

    $player->save();

    return $player;
  }

  public function attachAvatar(string $playerId, array $avatar)
  {
    $folderPath = $this->containerService->get('settings')['files']['folder_path'];
    $uploadHandler = new UploadHandler($folderPath);

    // Define validation ruleset
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

    // Use UUID for avatar name
    $uploadHandler->setSanitizerCallback(function ($name) {
      $extension = strtolower(substr($name, strrpos($name, '.') + 1, 10));
      return Uuid::uuid4() . '.' . $extension;
    });

    $result = $uploadHandler->process($avatar);

    if ($result->isValid()) {
      // Construct the full avatar URL => the coupling between the application service and
      // the web layer is a smell, should be avoided in future.
      $avatar = 'http://' . $_SERVER['HTTP_HOST'] . '/' .
        $this->containerService->get('settings')['files']['public_folder'] . '/' .
        $result->name;

      $player = $this->getPlayer($playerId);
      $oldAvatar = $player->getAvatarFileName();
      $player->setAvatar($avatar);

      $player->save();

      // Remove old avatar if existent
      if ($oldAvatar != null) {
        unlink($folderPath . $oldAvatar);
      }

      $result->confirm();

      return $player;
    } else {
      // Validation of uploaded avatar failed
      throw new InvalidArgumentException(implode(', ', $result->getMessages()));
    }
  }

  public function getPlayers(int $perPage = 5, int $page = 1)
  {
    return Player::query()
      ->with('teams')
      ->with('teams.players')
      ->orderBy('created_at', 'desc')
      ->simplePaginate($perPage, ['id', 'username'], 'page', $page);
  }

  public function getPlayer(string $id)
  {
    return Player::query()
      ->with('teams')
      ->with('teams.players')
      ->find($id);
  }
}
