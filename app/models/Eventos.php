<?php

/*
 * Este Archivo es parte del Framework Ocrend Moldeado Especialmente para esta StartUp(GetJob)
 *
 * (C) <f.andradevalenzuela@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace app\models;

use app\models as Model;
use Ocrend\Kernel\Models\Models;
use Ocrend\Kernel\Models\IModels;
use Ocrend\Kernel\Models\ModelsException;
use Ocrend\Kernel\Models\Traits\DBModel;
use Ocrend\Kernel\Router\IRouter;

/**
 * Modelo Eventos
 *
 * @author Felipe Andrade <f.andradevalenzuela@gmail.com>
 */

class Eventos extends Models implements IModels {
        /**
     * Característica para establecer conexión con base de datos.
     */
    use DBModel;



        public function getEventos() : array {

            return $this->db->query_select('SELECT * FROM event ORDER BY fecha DESC');

        }

        public function getEventosByUser() : array {
            $user = (new Model\Users)->getOwnerUser();
            $user = $user['id_user'];
            $query = $this->db->query_select("SELECT * FROM event WHERE user='$user' ORDER BY fecha DESC");
            if($query != false){
                return $query;
            }else{
                return array('nombre'=> 'No hay datos');
            }
        }
        public function getEventosById(int $id, string $select = '*') {
            $user = (new Model\Users)->getOwnerUser();
            $user = $user['id_user'];
           return $this->db->query_select("SELECT * FROM event WHERE id='$id' AND user='$user' LIMIT 1");
        }

        public function crearevento() : array {
        try {
             global $http;

            # Obtener los datos $_POST
            $name = $http->request->get('nombreEvento');
            $trabajadores = $http->request->get('trabajadoresQ');
            $func = $http->request->get('func');
            $fecha = $http->request->get('fecha');
            $id = $http->request->get('id');

            # Verificar que no están vacíos
            if ($this->functions->e($name, $trabajadores, $func, $fecha)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            $this->db->insert('event', array(
                'user' => $id,
                'nombre' => $name,
                'trabajadores' => $trabajadores,
                'funcion' => $func,
                'fecha' => $fecha
            ));

            return array('success' => 1, 'message' => 'Registrado con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }
        public function editar_evento() : array {
        try {
             global $http;

            # Obtener los datos $_POST
            $name = $http->request->get('nombreEvento');
            $trabajadores = $http->request->get('trabajadoresQ');
            $func = $http->request->get('func');
            $fecha = $http->request->get('fecha');
            $id = $http->request->get('id');

            # Verificar que no están vacíos
            if ($this->functions->e($name, $trabajadores, $func, $fecha)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            $this->db->update('event', array(
                'nombre' => $name,
                'trabajadores' => $trabajadores,
                'funcion' => $func,
                'fecha' => $fecha
            ),"id='$id'");

            return array('success' => 1, 'message' => 'Editado con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    // Contenido del modelo... 


    /**
     * __construct()
     */
    public function __construct(IRouter $router = null) {
        parent::__construct($router);
        $this->startDBConexion();
    }

    /**
     * __destruct()
     */
    public function __destruct() {
        parent::__destruct();
        $this->endDBConexion();
    }
}