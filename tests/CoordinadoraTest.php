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
        $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../');
        $dotenv->load();

        $apikey = '';
        $password_dispatche = '';
        $nit = '';

        $id_client = '';
        $user_guide = '';
        $password_guide = '';

        $this->webservice = new WebService($apikey, $password_dispatche, $nit, $id_client, $user_guide, $password_guide);
        $this->webservice->sandbox_mode(false);
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
        $cart_prods = [];

        /*$cart_prods[] = array(
            'ubl'      => '0',
            'alto'     => '1',
            'ancho'    => '2',
            'largo'    => '28',
            'peso'     => '1',
            'unidades' => '1', //900 gramos
        );*/

        $cart_prods[] = array(
            'ubl'      => '0',
            'alto'     => '2',
            'ancho'    => '4.5',
            'largo'    => '56',
            'peso'     => '1',
            'unidades' => '1', //900 gramos
        );


        /**
         *  tengo dos productos  que cada uno pesa menos de 1 kilo
         *
         * pero como solo me permite pasar  kilos le coloco mínimo 1 kilo
         *
         * puedo agrupar estos productos y pasarlos como un solo para que, sumando su dimensiones y peso ?
         *
         * ¨**
         */

        $params = array(
            'div'            => '01', //Div asociado a un acuerdo Coordinadora Mercantil, si no se tiene acuerdo el campo puede ir vacio.
            'cuenta'         => '2',
            'producto'       => '0',
            'origen'         => "11001000",
            'destino'        => '08001000',
            'valoracion'     => '5000',
            'nivel_servicio' => '0',
            'detalle'        => array(
                'item' => $cart_prods
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
            'alto' => '2.5',
            'ancho' => '1.5',
            'largo' => '4.5',
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
            'codigo_cuenta' => "1",
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
        var_dump($data);
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

    public function testPrintRotulos()
    {
        $params = array(
            'id_rotulo' => '55',
            'codigos_remisiones' => array(
                '90272500056'
            )
        );

        $data = $this->webservice->Guias_imprimirRotulos($params);
        $this->assertObjectHasAttribute('error', $data, false);
    }
}