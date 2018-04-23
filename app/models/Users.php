<?php

/*
 * This file is part of the Ocrend Framewok 2 package.
 *
 * (c) Ocrend Software <info@ocrend.com>
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
 * Controla todos los aspectos de un usuario dentro del sistema.
 *
 * @author Brayan Narváez <prinick@ocrend.com>
 */

class Users extends Models implements IModels {
    /**
     * Característica para establecer conexión con base de datos.
     */
    use DBModel;

    /**
     * Máximos intentos de inincio de sesión de un usuario
     *
     * @var int
     */
    const MAX_ATTEMPTS = 5;

    /**
     * Tiempo entre máximos intentos en segundos
     *
     * @var int
     */
    const MAX_ATTEMPTS_TIME = 120; # (dos minutos)

    /**
     * Log de intentos recientes con la forma 'email' => (int) intentos
     *
     * @var array
     */
    private $recentAttempts = array();

      /**
       * Hace un set() a la sesión login_user_recentAttempts con el valor actualizado.
       *
       * @return void
       */
    private function updateSessionAttempts() {
        global $session;

        $session->set('login_user_recentAttempts', $this->recentAttempts);
    }

    /**
     * Genera la sesión con el id del usuario que ha iniciado
     *
     * @param string $pass : Contraseña sin encriptar
     * @param string $pass_repeat : Contraseña repetida sin encriptar
     *
     * @throws ModelsException cuando las contraseñas no coinciden
     */
    private function checkPassMatch(string $pass, string $pass_repeat) {
        if ($pass != $pass_repeat) {
            throw new ModelsException('Las contraseñas no coinciden.');
        }
    }

    /**
     * Verifica el rut introducido, tanto el formato como su existencia en el sistema
     *
     * @param string $rut: Rut del trabajador
     *
     */
    private function checkRut(string $rut,string $id_user='0') {
        # Existencia de email
        if ($rut != '0' ){
          $rut = $this->db->scape($rut);
          $query = $this->db->select('rut', 'users', "rut='$rut' and id_user<>$id_user", 'LIMIT 1');
          if (false !== $query) {
              throw new ModelsException('El Rut introducido ya se encuentra asignado.');
          }
        }
    }

    /**
     * Restaura los intentos de un usuario al iniciar sesión
     *
     * @param string $rut: rut del usuario a restaurar
     *
     * @throws ModelsException cuando hay un error de lógica utilizando este método
     * @return void
     */
    private function restoreAttempts(string $rut) {
        if (array_key_exists($rut, $this->recentAttempts)) {
            $this->recentAttempts[$rut]['attempts'] = 0;
            $this->recentAttempts[$rut]['time'] = null;
            $this->updateSessionAttempts();
        } else {
            throw new ModelsException('Error lógico');
        }

    }

    /**
     * Genera la sesión con el id del usuario que ha iniciado
     *
     * @param array $user_data: Arreglo con información de la base de datos, del usuario
     *
     * @return void
     */
    private function generateSession(array $user_data) {
        global $session, $config;

        $session->set($config['sessions']['unique'] . '_user_id',(int) $user_data['id_user']);
    }

    /**
     * Verifica en la base de datos, el rut y contraseña ingresados por el usuario
     *
     * @param string $rut: rut del usuario que intenta el login
     * @param string $pass: Contraseña sin encriptar del usuario que intenta el login
     *
     * @return bool true: Cuando el inicio de sesión es correcto
     *              false: Cuando el inicio de sesión no es correcto
     */
    private function authentication(string $rut,string $pass) : bool {
        $rut = $this->db->scape($rut);
        $query = $this->db->select('id_user,pass','users',"rut='$rut'",'LIMIT 1');

        # Incio de sesión con éxito
        if(false !== $query && Strings::chash($query[0]['pass'],$pass)) {

            # Restaurar intentos
            $this->restoreAttempts($rut);

            # Generar la sesión
            $this->generateSession($query[0]);
            return true;
        }

        return false;
    }

    /**
     * Establece los intentos recientes desde la variable de sesión acumulativa
     *
     * @return void
     */
    private function setDefaultAttempts() {
        global $session;

        if (null != $session->get('login_user_recentAttempts')) {
            $this->recentAttempts = $session->get('login_user_recentAttempts');
        }
    }

    /**
     * Establece el intento del usuario actual o incrementa su cantidad si ya existe
     *
     * @param string $rut: rut del usuario
     *
     * @return void
     */
    private function setNewAttempt(string $rut) {
        if (!array_key_exists($rut, $this->recentAttempts)) {
            $this->recentAttempts[$rut] = array(
                'attempts' => 0, # Intentos
                'time' => null # Tiempo
            );
        }

        $this->recentAttempts[$rut]['attempts']++;
        $this->updateSessionAttempts();
    }

    /**
     * Controla la cantidad de intentos permitidos máximos por usuario, si llega al límite,
     * el usuario podrá seguir intentando en self::MAX_ATTEMPTS_TIME segundos.
     *
     * @param string $rut: rut del usuario
     *
     * @throws ModelsException cuando ya ha excedido self::MAX_ATTEMPTS
     * @return void
     */
    private function maximumAttempts(string $rut) {
        if ($this->recentAttempts[$rut]['attempts'] >= self::MAX_ATTEMPTS) {

            # Colocar timestamp para recuperar más adelante la posibilidad de acceso
            if (null == $this->recentAttempts[$rut]['time']) {
                $this->recentAttempts[$rut]['time'] = time() + self::MAX_ATTEMPTS_TIME;
            }

            if (time() < $this->recentAttempts[$rut]['time']) {
                # Setear sesión
                $this->updateSessionAttempts();
                # Lanzar excepción
                throw new ModelsException('Ya ha superado el límite de intentos para iniciar sesión.');
            } else {
                $this->restoreAttempts($rut);
            }
        }
    }

    /**
     * Realiza la acción de login dentro del sistema
     *
     * @return array : Con información de éxito/falla al inicio de sesión.
     */
    public function login() : array {
        try {
            global $http;

            # Definir de nuevo el control de intentos
            $this->setDefaultAttempts();

            # Obtener los datos $_POST
            $rut = strtolower($http->request->get('rut'));
            $pass = $http->request->get('pass');

            # Verificar que no están vacíos
            if ($this->functions->e($rut, $pass)) {
                throw new ModelsException('Credenciales incompletas.');
            }

            # Añadir intentos
            $this->setNewAttempt($rut);

            # Verificar intentos
            $this->maximumAttempts($rut);

            # Autentificar
            if ($this->authentication($rut, $pass)) {
                return array('success' => 1, 'message' => 'Conectado con éxito.');
            }

            throw new ModelsException('Credenciales incorrectas.');

        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    /**
     * Realiza la acción de registro dentro del sistema
     *
     * @return array : Con información de éxito/falla al registrar el usuario nuevo.
     */
    public function register() : array {
        try {
            global $http;

            # Obtener los datos $_POST
            $name = $http->request->get('name');
            $rut = $http->request->get('rut');
            $mail = $http->request->get('mail');
            $pass = $http->request->get('pass');
            $pass_repeat = $http->request->get('pass_repeat');

            # Verificar que no están vacíos
            if ($this->functions->e($name, $rut, $pass, $pass_repeat,$mail)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            # Verificar rut
           $this->checkRut($rut);

            # Veriricar contraseñas
            $this->checkPassMatch($pass, $pass_repeat);

            # Registrar al usuario
            $this->db->insert('users', array(
                'name' => $name,
                'rut' => $rut,
                'email' => $mail,
                'pass' => Strings::hash($pass)
            ));

            # Iniciar sesión
            $this->generateSession(array(
                'id_user' => $this->db->lastInsertId()
            ));

            return array('success' => 1, 'message' => 'Registrado con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    /**
      * Envía un correo electrónico al usuario que quiere recuperar la contraseña, con un token y una nueva contraseña.
      * Si el usuario no visita el enlace, el sistema no cambiará la contraseña.
      *
      * @return array<string,integer|string>
    */
    public function lostpass() {
        try {
            global $http, $config;

            # Obtener datos $_POST
            $rut = $http->request->get('rut');

            # Campo lleno
            if ($this->functions->emp($rut)) {
                throw new ModelsException('El campo rut debe estar lleno.');
            }

            # Filtro
            $rut = $this->db->scape($rut);

            # Obtener información del usuario
            $user_data = $this->db->select('id_user,name', 'users', "rut='$rut'", 'LIMIT 1');

            # Verificar correo en base de datos
            if (false === $user_data) {
                throw new ModelsException('El rut no está registrado en el sistema.');
            }

            # Generar token y contraseña
            $token = md5(time());
            $pass = uniqid();

            # Construir mensaje y enviar mensaje
            $HTML = 'Hola <b>'. $user_data[0]['name'] .'</b>, ha solicitado recuperar su contraseña perdida, si no ha realizado esta acción no necesita hacer nada.
					<br />
					<br />
					Para cambiar su contraseña por <b>'. $pass .'</b> haga <a href="'. $config['site']['url'] . 'lostpass/cambiar/&token='.$token.'&user='.$user_data[0]['id_user'].'" target="_blank">clic aquí</a>.';

            # Enviar el correo electrónico
            $dest = array();
			$dest[$rut] = $user_data[0]['name'];
			$rut = Emails::send_mail($dest,Emails::plantilla($HTML),'Recuperar contraseña perdida');

            # Verificar si hubo algún problema con el envío del correo
            if(false === $rut) {
                throw new ModelsException('No se ha podido enviar el correo electrónico.');
            }

            # Actualizar datos
            $id_user = $user_data[0]['id_user'];
            $this->db->update('users',array(
                'tmp_pass' => Strings::hash($pass),
                'token' => $token
            ),"id_user='$id_user'",'LIMIT 1');

            return array('success' => 1, 'message' => 'Se ha enviado un enlace a su correo electrónico.');
        } catch(ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    /**
     * Cambia la contraseña de un usuario en el sistema, luego de que éste haya solicitado cambiarla.
     * Luego retorna al sitio de inicio con la variable GET success=(bool)
     *
     * La URL debe tener la forma URL/lostpass/cambiar/&token=TOKEN&user=ID
     *
     * @return void
     */
    public function changeTemporalPass() {
        global $config, $http;

        # Obtener los datos $_GET
        $id_user = $http->query->get('user');
        $token = $http->query->get('token');

        if (!$this->functions->emp($token) && is_numeric($id_user) && $id_user >= 1) {
            # Filtros a los datos
            $id_user = $this->db->scape($id_user);
            $token = $this->db->scape($token);
            # Ejecutar el cambio
            $this->db->query("UPDATE users SET pass=tmp_pass, tmp_pass='', token=''
            WHERE id_user='$id_user' AND token='$token' LIMIT 1;");
            # Éxito
            $success = true;
        }

        # Devolover al sitio de inicio
        $this->functions->redir($config['site']['url'] . '?sucess=' . (int) isset($success));
    }

    /**
     * Desconecta a un usuario si éste está conectado, y lo devuelve al inicio
     *
     * @return void
     */
    public function logout() {
        global $session, $config;

        if(null != $session->get($config['sessions']['unique'] . '_user_id')) {
            $session->remove($config['sessions']['unique'] . '_user_id');
        }

        $this->functions->redir();
    }

    /**
     * Obtiene datos de un usuario según su id en la base de datos
     *
     * @param int $id: Id del usuario a obtener
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios
     *
     * @return false|array con información del usuario
     */
    public function getUserById(int $id, string $select = '*') {
        return $this->db->select($select,'users',"id_user='$id'",'LIMIT 1');
    }

    /**
     * Obtiene a todos los usuarios
     *
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios
     *
     * @return false|array con información de los usuarios
     */
    public function getUsers(string $select = '*') {
        return $this->db->select($select,'users');
    }
    public function getComunas(string $select = '*') {
        return $this->db->select($select,'comunas');
    }
    /**
     * Obtiene datos del usuario conectado actualmente
     *
     * @param string $select : Por defecto es *, se usa para obtener sólo los parámetros necesarios
     *
     * @throws ModelsException si el usuario no está logeado
     * @return array con datos del usuario conectado
     */
    public function getOwnerUser(string $select = '*') : array {
        if(null !== $this->id_user) {

            $user = $this->db->select($select,'users',"id_user='$this->id_user'",'LIMIT 1');

            # Si se borra al usuario desde la base de datos y sigue con la sesión activa
            if(false === $user) {
                $this->logout();
            }

            return $user[0];
        }

        throw new \RuntimeException('El usuario no está logeado.');
    }

    public function traer_datos(): array {
        try {
            global $http;

            #Obtener los datos $_POST
            $id = $http->request->get('id');

           $datos = $this->db->query_select("SELECT * FROM users WHERE id_user=$id");
            //
            return array('success' => 1, 'datos' => $datos);
        }catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }

    public function resetpass() : array {
        try {
            global $http;

            # Obtener los datos $_POST
            $id_user = $this->id_user;
            $pass = $http->request->get('pass');
            $pass_repeat = $http->request->get('pass_repeat');

            # Verificar que no están vacíos
            if ($this->functions->e($pass, $pass_repeat)) {
                throw new ModelsException('Todos los datos son necesarios');
            }

            # Veriricar contraseñas
            $this->checkPassMatch($pass, $pass_repeat);

            $pass = Strings::hash($pass);

            # Actualiza contraseña
            $this->db->query("UPDATE users SET pass='$pass', tmp_pass='', token=''
            WHERE id_user='$id_user' LIMIT 1;");

            return array('success' => 1, 'message' => 'Password actualizada con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }
        public function actualizar() : array {
        try {
            global $http;

            # Obtener los datos $_POST
            $id_user = $this->id_user;
            $name = $http->request->get('name');
            $mail = $http->request->get('mail');
            $n_telefono = $http->request->get('n_telefono');
            $fecha_nacimiento = $http->request->get('fecha_nacimiento');
            $comuna = $http->request->get('comuna');
            $sexo = $http->request->get('sexo');
            $bip = $http->request->get('bip');

            # Verificar que no están vacíos
            if ($this->functions->e($name, $mail)) {
                throw new ModelsException('Todos los datos son necesarios');
            }


            # Actualiza usuario
            $this->db->query("UPDATE users SET 
            name='$name', 
            email='$mail', 
            n_telefono='$n_telefono',
            fecha_nacimiento='$fecha_nacimiento',
            comuna='$comuna',
            sexo='$sexo',
            bip='$bip'
            WHERE id_user='$id_user' LIMIT 1;");

            return array('success' => 1, 'message' => 'Datos actualizados con éxito.');
        } catch (ModelsException $e) {
            return array('success' => 0, 'message' => $e->getMessage());
        }
    }


    /**
     * Instala el módulo de usuarios en la base de datos para que pueda funcionar correctamete.
     *
     * @throws \RuntimeException si no se puede realizar la query
     */
    public function install() {
        if (!$this->db->query("
            CREATE TABLE IF NOT EXISTS `users` (
                `id_user` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `email` varchar(150) NOT NULL,
                `pass` varchar(90) NOT NULL,
                `tmp_pass` varchar(90) NOT NULL DEFAULT '',
                `token` varchar(90) NOT NULL DEFAULT '',
                PRIMARY KEY (`id_user`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ")) {
            throw new \RuntimeException('No se ha podido instalar el módulo de usuarios.');
        }

        dump('Módulo instalado correctamente, el método <b>(new Model\Users)->install()</b> puede ser borrado.');
        exit(1);
    }

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
