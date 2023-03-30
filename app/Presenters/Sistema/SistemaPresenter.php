<?php

namespace App\Presenters\Sistema;
use App\Presenters\Presenter;
use App\Services\Imagen;

class SistemaPresenter extends Presenter
{
  private $folderImg = 'gp_images/config';
  // private $imgFondo = "/dist/img/international.jpg";
  // private $imgFondoLogin = "/dist/img/international.jpg";
  private $imgLogo = "images/4.png";
  private $imgLogin = "images/ganapuntoscl2.png";

  public function getLogo() {
    return (new Imagen($this->model->assets['logo'] ?? null, $this->folderImg, $this->imgLogo))->call();
  }

  public function getBackgroundLogin() {
    return (new Imagen($this->model->assets['background'] ?? null, $this->folderImg, $this->imgLogin))->call();
  }
}
