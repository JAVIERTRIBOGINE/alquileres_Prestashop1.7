
<?php



class AlquilerOrder extends ObjectModel
{

    public $fecha;

    public $id_customer;

    public $hora_reservada;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'alquiler',
        'primary' => 'id_alquiler_order',
        'multilang' => false,
        'fields' => array(
            'id_alquiler' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
//            'hora_reservada' => array('type' => self::TYPE_INT, 'validate' => 'isInt')
        ),
    );

    public function __construct($id_alquiler_order = null)
    {
        parent::__construct();
    }


    public function getIdOrderFromIdAlquiler($idAlquiler)
    {
        return DB::getInstance()->getValue('select id_order from ps_alquiler_order where id_alquiler = '-$idAlquiler);
    }

    public function getIdAlquilerFromIdOrder($idOrder)
    {
        return DB::getInstance()->getValue('select id_alquiler from ps_alquiler_order where id_order = '-$idOrder);
    }

}


?>
