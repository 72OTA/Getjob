<?php

/*
 * Este Archivo es parte del Framework Ocrend Moldeado Especialmente para esta StartUp(GetJob)
 *
 * (C) <f.andradevalenzuela@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
*/

use app\models as Model;

/**
    * Inicio de sesión
    *
    * @return json
*/  
$app->post('/login', function() use($app) {
    $u = new Model\Users;   

    return $app->json($u->login());   
});

/**
    * Registro de un usuario
    *
    * @return json
*/
$app->post('/register', function() use($app) {
    $u = new Model\Users; 

    return $app->json($u->register());   
});

/**
    * Recuperar contraseña perdida
    *
    * @return json
*/
$app->post('/lostpass', function() use($app) {
    $u = new Model\Users; 

    return $app->json($u->lostpass());   
});
/**
    * Traer datos del usuario
    *
    * @return json
*/
$app->post('/ver_usuario', function() use($app) {
    $u = new Model\Users; 

    return $app->json($u->traer_datos());   
});
/**
    * Cambiar Contraseña
    *
    * @return json
*/
$app->post('/resetpass', function() use($app) {
    $u = new Model\Users;

    return $app->json($u->resetpass());
});
/**
    * Actualizar Usuario
    *
    * @return json
*/
$app->post('/actualizar', function() use($app) {
    $u = new Model\Users;

    return $app->json($u->actualizar());
});
/**
    * Crear Empresa
    *
    * @return json
*/
$app->post('/crearempresa', function() use($app) {
    $u = new Model\Empresas;

    return $app->json($u->crearempresa());
});
/**
    * Crear Evento
    *
    * @return json
*/
$app->post('/crearevento', function() use($app) {
    $u = new Model\Eventos;

    return $app->json($u->crearevento());
});
/**
    * Editar Evento
    *
    * @return json
*/
$app->post('/editar_evento', function() use($app) {
    $u = new Model\Eventos;

    return $app->json($u->editar_evento());
});