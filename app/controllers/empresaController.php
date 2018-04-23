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
 * Controlador empresa/
 *
 * @author Felipe Andrade <f.andradevalenzuela@gmail.com>
*/
  
class empresaController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router,array(
            'users_logged' => true
        ));
               global $config;
        
		        switch($this->method){
          case 'crear':
                 echo $this->template->render('empresa/empresacrear', array(
                'user' => (new Model\Users)->getOwnerUser(),
             ));    
              break;    
            case 'visualizar':
                 echo $this->template->render('empresa/visorempresa', array(
                'user' => (new Model\Users)->getOwnerUser(),
                'empresa' => (new Model\Empresas)->ver_empresa()
             ));    
              break;
          default:
                echo $this->template->render('empresa/empresahome',array(
                'user' => (new Model\Users)->getOwnerUser()
             ));
            break;
        }
    }

}