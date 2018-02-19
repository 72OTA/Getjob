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
 * Controlador eventos/
 *
 * @author Brayan Narváez <prinick@ocrend.com>
*/
  
class eventosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router);   
        $e = new Model\Eventos;
		echo $this->template->render('eventos/eventos');

    }

}