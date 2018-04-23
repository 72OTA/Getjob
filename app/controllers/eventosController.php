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
 * Controlador eventos/
 *
 * @author Felipe Andrade <f.andradevalenzuela@gmail.com>
*/
  
class eventosController extends Controllers implements IControllers {

    public function __construct(IRouter $router) {
        parent::__construct($router,array(
		'users_logged' => true
        ));   
               global $config;

        switch($this->method){
          case 'crear':
                 echo $this->template->render('eventos/eventcrear', array(
                     'fecha' => date('Y-m-d'),
                    'user' => (new Model\Users)->getOwnerUser()
                     
             ));    
              break;
            case 'visualizar':
                 echo $this->template->render('eventos/visoreventos', array(
                     'evento' => (new Model\Eventos)->getEventos(),
                    'user' => (new Model\Users)->getOwnerUser(),
                    'fecha' => date('Y-m-d'),
             ));    
              break;
            case 'misEventos':
                 echo $this->template->render('eventos/miseventos', array(
                     'evento' => (new Model\Eventos)->getEventosByUser(),
                      'fecha' => date('Y-m-d'),
                    'user' => (new Model\Users)->getOwnerUser()
             ));    
              break;
            case "editar_evento":
                if($this->isset_id and false !== ($evento=(new Model\Eventos)->getEventosById($router->getId(true)))){
                    echo $this->template->render('eventos/editar_evento', array(
                    'evento'=>$evento[0],
                    'fecha' => date('Y-m-d'),
                    'user' => (new Model\Users)->getOwnerUser()
                    ));
                }
                 else {
                    $this->functions->redir($config['site']['url'] . 'evento/&error=true');
                }
            break;
          default:
                echo $this->template->render('eventos/eventhome',array(
                'user' => (new Model\Users)->getOwnerUser()
             ));
            break;
        }
    }

}