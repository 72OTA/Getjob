<?php

/*
 * Este Archivo es parte del Framework Ocrend Moldeado Especialmente para esta StartUp(GetJob)
 *
 * (C) <f.andradevalenzuela@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

namespace app\controllers;

use app\models as Model;
use Ocrend\Kernel\Router\IRouter;
use Ocrend\Kernel\Controllers\Controllers;
use Ocrend\Kernel\Controllers\IControllers;

/**
 * Controlador home/
 *
 * @author Felipe Andrade <f.andradevalenzuela@gmail.com>
*/

class homeController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);
        global $config;

  if (!isset($this->user['id_user']))
      echo $this->template->render('home/home');
  elseif($this->user['rol'] == 0){
      //redirecciona a pagina de inicio de usuario
      echo $this->template->render('home/principal',array(
          'user'=> (new Model\Users)->getOwnerUser(),
      ));
  }elseif ($this->user['rol'] == 1) {
       echo $this->template->render('home/empresaprincipal',array(
          'user'=> (new Model\Users)->getOwnerUser(),
      ));
  }
  
    }

}
