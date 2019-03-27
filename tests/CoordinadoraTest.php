<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 27/01/19
 * Time: 03:24 PM
 */

use PHPUnit\Framework\TestCase;
use Coordinadora\WebService;

class CoordinadoraTest extends TestCase
{
    public $webservice;

    public function setUp()
    {
        $apikey = '';
        $password_dispatche = '';
        $nit = '';

        $id_client = '';
        $user_guide = '';
        $password_guide = '';

        $this->webservice = new WebService($apikey, $password_dispatche, $nit, $id_client, $user_guide, $password_guide);
        $this->webservice->sandbox_mode(true);
    }


    public function testCotizadorDepartamentos()
    {
        $data = $this->webservice->Cotizador_departamentos();
        $this->assertInternalType('object', $data);
    }

    public function testCotizadorCiudades()
    {
        $data = $this->webservice->Cotizador_ciudades();
        $this->assertInternalType('object', $data);
    }

    public function testCotizar()
    {

        $cart_prods = array(
            'ubl'      => '0',
            'alto'     => '70',
            'ancho'    => '100',
            'largo'    => '50',
            'peso'     => '1',
            'unidades' => '1',
        );

        $params = array(
            'div'            => '',
            'cuenta'         => '2',
            'producto'       => '0',
            'origen'         => "13001000",
            'destino'        => '25175000',
            'valoracion'     => '50000',
            'nivel_servicio' => array(0),
            'detalle'        => array(
                'item' => $cart_prods,
            )
        );

        $data = $this->webservice->Cotizador_cotizar($params);

        $this->assertObjectHasAttribute('flete_total', $data);
    }


    public function testGenerateGuide()
    {

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

        $data = $this->webservice->Guias_generarGuia($params);
        $this->assertObjectHasAttribute('codigo_remision', $data);

    }

    public function testEditGuide()
    {
        
    }

    public function testcancelGuide()
    {
        $params = array(
          'codigo_remision' => '85820005432'
        );

        $data = $this->webservice->Guias_anularGuia($params);
        $this->assertTrue($data);
    }


    public function testGenerateDispatch()
    {
        $params = array(
            'guias' => array(
                '85820000083'
            ),
            'margen_izquierdo' => '',
            'margen_superior' => '',
            'tipo_impresion' => 'POS_PDF'
        );

        $data = $this->webservice->Guias_generarDespacho($params);
        $this->assertArrayHasKey(0,$data);
    }

    public function testTrackingGuideSimple()
    {
        $params = array(
           'codigos_remision' => array(
               '85820005433'
           )
        );

        $data = $this->webservice->Guias_rastreoSimple($params);
        $this->assertArrayHasKey(0,$data);

    }
}