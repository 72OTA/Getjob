<?php

/*
 * This file is part of the Ocrend Framewok 2 package.
 *
 * (c) Ocrend Software <info@ocrend.com>
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
 * Controlador administracion/
 *
 * @author Jorge Jara H. <jjara@wys.cl>
*/

class perfilController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router,array(
            'users_logged' => true
        ));
        global $config;

        switch($this->method){
          case 'usuario':
                 echo $this->template->render('usuarios/perfil', array(
                'user' => (new Model\Users)->getOwnerUser(),
                'comuna' => (new Model\Users)->getComunas()
             ));    
              break;
          default:
                echo $this->template->render('home/principal',array(
                'user' => (new Model\Users)->getOwnerUser()
             ));
            break;
        }
    }

}
