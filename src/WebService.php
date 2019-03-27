<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 27/01/19
 * Time: 11:08 AM
 */

namespace Coordinadora;

class WebService
{

    const SANDBOX_URL_TRACKING_DISPATCHES = 'http://sandbox.coordinadora.com/ags/1.5/server.php?wsdl';

    const URL_TRACKING_DISPATCHES = 'https://ws.coordinadora.com/ags/1.5/server.php?wsdl';

    const SANDBOX_URL_GUIDES = 'http://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php';

    const URL_GUIDES = 'http://guias.coordinadora.com/ws/guias/1.6/server.php';

    private $_apikey;

    private $_password_dispatches;

    private $nit;

    private $id_client;

    private $user_guide;

    private $password_guide;

    private static $sandbox = false;

    /**
     * WebService constructor.
     * @throws \Exception
     */
    public function __construct()
    {

        $i = func_num_args();

        if ($i !== 6)
            throw new \Exception("Invalid arguments");


        $this->_apikey = func_get_arg(0);
        $this->_password_dispatches = func_get_arg(1);
        $this->nit = func_get_arg(2);

        $this->id_client = func_get_arg(3);
        $this->user_guide = func_get_arg(4);
        $this->password_guide = func_get_arg(5);
    }

    public function sandbox_mode($status = false)
    {
        if ($status){
            self::$sandbox = true;
        }
        return $this;
    }

    public static function get_url_tracking_dispatches()
    {
        if (self::$sandbox)
            return self::SANDBOX_URL_TRACKING_DISPATCHES;
        return self::URL_TRACKING_DISPATCHES;
    }

    public static function get_url_guides()
    {
        if (self::$sandbox)
            return self::SANDBOX_URL_GUIDES;
        return self::URL_GUIDES;
    }

    public static function url_soap($name_function)
    {

        $url_soap = self::isGuide($name_function) === false ? self::get_url_guides() : self::get_url_tracking_dispatches();

        return $url_soap;
    }

    public static function isGuide($name_function)
    {
        return strpos($name_function, 'Guias') === false;
    }

    protected function _access_connect_dispatches()
    {
        return array(
            'apikey' => $this->_apikey,
            'clave' => $this->_password_dispatches
        );
    }

    protected function _access_connect_guides()
    {
        return array(
            'id_cliente' => $this->id_client,
            'usuario' => $this->user_guide,
            'clave' => hash('sha256', $this->password_guide)
        );
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function Cotizador_departamentos()
    {
        return self::call_soap(__FUNCTION__);

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public static function Cotizador_ciudades()
    {
        return self::call_soap(__FUNCTION__);

    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Cotizador_cotizar($params)
    {
        $body =  array(
            'p' => array_merge($params, array('nit'  => ''), $this->_access_connect_dispatches()),
        );

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Recaudos_consultar($params)
    {
        $body = array(
            'p' => array_merge($params, $this->_access_connect_dispatches()),
        );

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Recogidas_programar($params)
    {
        $body = array(
            'p' => array_merge($params, $this->_access_connect_dispatches()),
        );

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Guias_generarGuia($params)
    {

        $body = (object)array_merge($params, $this->_access_connect_guides());

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Guias_editarGuia($params)
    {
        $body = (object)array_merge($params, $this->_access_connect_guides());

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Guias_generarDespacho($params)
    {
        $body = (object)array_merge($params, $this->_access_connect_guides());

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Guias_anularGuia($params)
    {
        $body = (object)array_merge($params, $this->_access_connect_guides());

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function Guias_rastreoSimple($params)
    {
        $body = (object)array_merge($params, $this->_access_connect_guides());

        return $this->call_soap(__FUNCTION__, $body);
    }

    /**
     * @param $name_function
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    private  static function call_soap($name_function, $params = array('p' => array()))
    {

        if (self::isGuide($name_function) === false){
            $client = New \SoapClient( null, array("location"  => self::url_soap($name_function),
                "uri"         => self::url_soap($name_function),
                "use"         => SOAP_LITERAL,
                "trace"       => true,
                "exceptions"  => true,
                "soap_version"=> SOAP_1_2,
                "connection_timeout"=> 30,
                "encoding"=> "utf-8"));
        }else{
            $client = New \SoapClient(self::url_soap($name_function));
        }

        try{
            $data = $client->$name_function($params);

            $name_function_result = $name_function . "Result";

            $res = isset($data->$name_function_result) ? $data->$name_function_result : $data;
            return $res;
        }catch (\Exception $exception){
            throw new  \Exception($exception->getMessage());
        }

    }
}