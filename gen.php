<?php

/*
 * Este Archivo es parte del Framework Ocrend Moldeado Especialmente para esta StartUp(GetJob)
 *
 * (C) <f.andradevalenzuela@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Ocrend\Kernel\Generator as PHPGen;

# Definir ruta de acceso permitida
define('API_INTERFACE', '');
define('GENERATOR', true);

# Conexión con el framework
require 'Ocrend/vendor/autoload.php';
require 'Ocrend/autoload.php';
require 'Ocrend/Kernel/Config/Start.php';

# Lanzar el generador
new PHPGen\Generator($argv);