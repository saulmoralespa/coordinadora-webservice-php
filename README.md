Coordinadora Webservice PHP
============================================================

## Installation

Use composer package manager


```bash
composer require saulmoralespa/coordinadora-webservice-php
```

```php
// ... please, add composer autoloader first
include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// import webservice class
use Coordinadora\WebService;

$apikey = ''; // your apikey of Coordinadora
$password = '' // your password of Coordinadora
$nit = '' //your nit

//guides
$id_client = ''; your id client
$user_guide = ''; your user
$password_guide = ''; your password


try{
    $coordinadora = WebService(apikey, $password, $nit, $id_client, $user_guide, $password_guide);
    $coordinadora->sandbox_mode(true); //true for tests or false for production
}
catch (\Exception $exception){
    echo $exception->getMessage();
}

```

### Cotizador_departamentos


```php
$data = WebService::Cotizador_departamentos();
```

### Cotizador_ciudades


```php
$data = WebService::Cotizador_ciudades();
```

### Cotizador_cotizar


```php
$cart_prods = array(
        'ubl' => '0', //Código de la UBL, 0=>Automatica, 1=>Mercancia
        'alto' => "60",
        'ancho' => "100",
        'largo' => '70',
        'peso' => '1',
        'unidades' => '1'
    );

    $params = array(
        'div' => "01", //Asociado a un acuerdo Coordinadora Mercantil
        'cuenta' => "2",
        'producto' => "0",
        'origen' => '05088000',
        'destino' => '25099000',
        'valoracion' => '90000',
        'nivel_servicio' => array(0),
        'detalle' => array(
            'item' => $cart_prods
        )
    );

try{
    $data = $coordinadora->Cotizador_cotizar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Recaudos_consultar

```php
$params = array(
'referencia' => '',
'codigo_remision' => ''
);

try{
    $data = $coordinadora->Recaudos_consultar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Recogidas_programar

```php

//view http://sandbox.coordinadora.com/ags/1.4/server.php?doc#Recogidas_solicitarIn

$params = array(
'modalidad' => 2,
'fecha_recogida' => '2019-03-10',
'ciudad_origen' => '05088000',
'ciudad_destino' => '25099000'
);

try{
    $data = $coordinadora->Recogidas_programar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Recogidas_seguimiento

```php

$params = array(
'id_recogida' => 2,
'nit_cliente' => '',
'div_cliente' => '',
'referencia' => ''
);

try{
    $data = $coordinadora->Recogidas_seguimiento($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Recogidas_seguimientoPorFecha

```php

$params = array(
'fecha_inicial' => '',
'fecha_final' => ''
);

try{
    $data = $coordinadora->Recogidas_seguimientoPorFecha($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Recogidas_seguimientoFCDetalladoPorReferencia

```php

$params = array(
'nit_cliente' => '',
'div_cliente' => '',
'referencia' = ''
);

try{
    $data = $coordinadora->Recogidas_seguimientoFCDetalladoPorReferencia($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```


### Recogidas_programarFC

```php

//view http://sandbox.coordinadora.com/ags/1.4/server.php?doc#Recogidas_solicitarIn

$params = array(
'modalidad' => 2,
'fecha_recogida' => '2019-03-10',
'ciudad_origen' => '05088000',
'ciudad_destino' => '25099000'
);

try{
    $data = $coordinadora->Recogidas_programar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Seguimiento_simple

```php

$params = array(
'codigo_remision' => '',
'nit' => '',
'div' => '',
'referencia' => '',
'imagen' => 0 //1 si se devuelve la imagen, 0 en caso contrario
'anexo' => 0 //1 si se devuelve el anexo de la imagen, 0 en caso contrario
);

try{
    $data = $coordinadora->Recogidas_programar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Seguimiento_detallado


```php

$params = array(
'codigo_remision' => '',
'nit' => '',
'div' => '',
'referencia' => '',
'imagen' => 0 //1 si se devuelve la imagen, 0 en caso contrario
'anexo' => 0 //1 si se devuelve el anexo de la imagen, 0 en caso contrario
);

try{
    $data = $coordinadora->Recogidas_programar($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}
```

### Guias_generarGuia

```php
$cart_prods = array();

        $cart_prods[] = (object)array(
            'ubl' => '0',
            'alto' => '70',
            'ancho' => '100',
            'largo' => '200',
            'peso' => '1',
            'unidades' => '1',
            'referencia' => 'referencepacket',
            'nombre_empaque' => 'name packet'
        );


        $cart_prods[] = (object)array(
            'ubl' => '0',
            'alto' => '70',
            'ancho' => '100',
            'largo' => '200',
            'peso' => '1',
            'unidades' => '1',
            'referencia' => 'referencepacket1',
            'nombre_empaque' => 'name packet'
        );


        $params = array(
            'codigo_remision' => '',
            'fecha' => '',
            'id_remitente' => "0",
            'nit_remitente' => '',
            'nombre_remitente' => 'shop Woo',
            'direccion_remitente' => 'calle 43 3-23',
            'telefono_remitente' => '3170044722',
            'ciudad_remitente' => '05001000',
            'nit_destinatario' => '0',
            'div_destinatario' => '0',
            'nombre_destinatario' => 'Pedro Perez',
            'direccion_destinatario' => 'calle 40 20-40',
            'ciudad_destinatario' => '05001000',
            'telefono_destinatario' => '3189023450',
            'valor_declarado' => '90000',
            'codigo_cuenta' => "2",
            'codigo_producto' => "0",
            'nivel_servicio' => "1",
            'linea' => '',
            'contenido' => 'nada',
            'referencia' => 'refeeradd',
            'observaciones' => '',
            'estado' => 'IMPRESO',
            'detalle' => $cart_prods,
            'cuenta_contable' => '',
            'centro_costos' => '',
            'recaudos' => '',
            'margen_izquierdo' => '',
            'margen_superior' => '',
            'id_rotulo' => '0',
            'usuario_vmi' => '',
            'formato_impresion' => '',
            'atributo1_nombre' => '',
            'atributo1_valor' => '',
            'notificaciones' => (object)array(
            ),
            'atributos_retorno' => (object)array(
                'nit' => '',
                'div' => '',
                'nombre' => '',
                'direccion' => '',
                'codigo_ciudad' => '',
                'telefono' => ''
            ),
            'nro_doc_radicados' => '',
            'nro_sobre' => '',
        );

try{
    $data = $coordinadora->Guias_generarGuia($params);
}
catch (\Exception $exception){
    echo $exception->getMessage();
}