
<?php



class Alquiler extends ObjectModel
{

    public $fecha;

    public $id_customer;

    public $hora_reservada;


    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'alquiler',
        'primary' => 'id_alquiler',
        'multilang' => false,
        'fields' => array(
            'fecha' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'hora_reservada' => array('type' => self::TYPE_INT, 'validate' => 'isInt')
        ),
    );

    public function __construct($fecha, $id_customer, $hora_reservada)
    {
        parent::__construct();
//      $this->id_semestre = $idSemestre;
        $this->fecha = $fecha;
        $this->id_customer = $id_customer;
        $this->hora_reservada = $hora_reservada;
    }


    public function getIdCustomer()
    {
        return $this->id_customer;
    }

}


?>
