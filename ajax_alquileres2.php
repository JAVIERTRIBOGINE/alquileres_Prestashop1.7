<?php
require_once ('../../config/config.inc.php');
require_once ('../../init.php');

    $opt = Tools::getValue('opt');
    if ($opt == "horas-fechas") {
        $fecha = Tools::getValue('fecha');
        $idCustomer = Tools::getValue('idCustomer');
        $value = array();
        $total_hours = array();
        $spaces = array(1, 2, 3); // aqui en la configuracion del modulo se ponen los espacios que se alquilan
        for ($a = 0; $a < 3; $a++) {
            $total_hours[$a] = array(9, 10, 11, 12, 13, 14, 15, 16, 18, 19, 20, 22);
        }
        $horas_reservadas = array();
        foreach ($spaces as $space) {
            $sql = 'select hora_alquiler from ps_alquiler where fecha_alquiler="' . $fecha . '" and espacio=' . $space;
            $horas_reservadas[($space - 1)] = Db::getInstance()->executeS($sql);
            if (($horas_reservadas[($space - 1)])) {
                foreach ($horas_reservadas[($space - 1)] as $key => $hora_reservada) {
                    foreach ($total_hours[($space - 1)] as &$hours) {
                        if (((int)$hora_reservada['hora_alquiler']) == $hours) {
                            $hours = 0;
                        }
                    }
                }
            }
        }
        die(json_encode(
            array(
                'evaluacion' => 'ok',
                'horas_reservadas' => $total_hours
            )));
    } elseif ($opt == 'insertar_horas'){
        $id_customer = (int)Tools::getValue('idCustomer');
        $mensaje = Tools::getValue('mensaje');
        $horas_reservadas = array();
        $idAlquileres = array();
        $horas_reservadas = Tools::getValue('registro_alquiler');
        foreach ($horas_reservadas as $horas_espacio) {
            $alquiler = new Alquiler();
            $alquiler->id_customer = $id_customer;
            $alquiler->fecha_alquiler= $horas_espacio[0];
            $alquiler->hora_alquiler = (int)$horas_espacio[2];
            $alquiler->espacio = (int)$horas_espacio[1];
            $alquiler->validado = 1;
            $alquiler->id_shop = 17;
            $alquiler->add();
            $idAlquileres[]=$alquiler->id;
        }
        die(json_encode(
            array (
                'evaluacion' => 'ok',
                'horas_reservadas' => $horas_reservadas,
                'idAlquileres' => $idAlquileres
            )
        ));
    }else{
        die(json_encode(
            array (
                'evaluacion' => 'no_result'
            )
        ));
    }
?>
