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
use Ocrend\Kernel\Helpers\Strings;
use Ocrend\Kernel\Helpers\Emails;

/**
 * Modelo Eventos
 *
 * @author Felipe Andrade <f.andradevalenzuela@gmail.com>
 */

class Empresas extends Models implements IModels {
        /**
     * Característica para establecer conexión con base de datos.
     */
    use DBModel;
    

    public function ver_empresa(): array {
            $user = (new Model\Users)->getOwnerUser();
            $user = $user['rut'];
          return $this->db->query_select("SELECT * FROM empresa WHERE id_empleado='$user' ");
    }

    public function crearempresa() : array {
        try {
            global $http;

            # Obtener los datos $_POST
            $name = $http->request->get('name');
            $rut = $http->request->get('rut');
            $rute = $http->request->get('rute');
            $rubro = $http->request->get('rubro');

            # Verificar que no están vacíos
            if ($this->functions->e($name, $rubro, $rute)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            $this->db->insert('empresa', array(
                'id_empleado' => $rut,
                'nombre' => $name,
                'rubro' => $rubro,
                'rut' => $rut
            ));

            return array('success' => 1, 'message' => 'Registrado con éxito.');
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