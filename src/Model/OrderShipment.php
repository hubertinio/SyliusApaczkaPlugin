<?php

declare(strict_types=1);

namespace Hubertinio\SyliusApaczkaPlugin\Model;

final class OrderShipment
{
    var $dimension1 = '';
    var $dimension2 = '';
    var $dimension3 = '';
    var $weight = '';
    private $shipmentTypeCode = '';
    private $shipmentValue = '';
    private $options = '';
    private $position = 0;
    private static $dictShipmentOptions = array('UBEZP', 'PRZES_NIETYP', 'DUZA_PACZKA');
    private static $dictShipmentTypeCode = array('LIST', 'PACZ', 'PALETA');

    function __construct($shipmentTypeCode = '', $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {
        if ($shipmentTypeCode == 'LIST') {
            $this->createShipment($shipmentTypeCode, 0, 0, 0, 0);
        } else {
            if ($dim1 != '' && $dim2 != '' && $dim3 != '' && $weight != '' && $shipmentTypeCode != '') {
                $this->createShipment($shipmentTypeCode, $dim1, $dim2, $dim3, $weight);
            }
        }
    }

    function getShipmentTypeCode() {
        return $this->shipmentTypeCode;
    }

    function setShipmentTypeCode($shipmentTypeCode) {
        if (!in_array($shipmentTypeCode, self::$dictShipmentTypeCode)) {
            throw new Exception('UNSUPPORTED service code: [' . $shipmentTypeCode . '] must be one of: ' . print_r(self::$dictShipmentTypeCode, 1));
        }

        $this->shipmentTypeCode = $shipmentTypeCode;
    }

    function getOptions() {
        return $this->options;
    }

    function addOrderOption($option) {
        if (!in_array($option, self::$dictShipmentOptions)) {
            throw new Exception('UNSUPPORTED order option: [' . $option . '] must be one of: ' . print_r(self::$dictShipmentOptions, 1));
        }

        if ($this->options == "") {
            $this->options = array('string' => $option);
        } else if (!is_array($this->options['string'])) {
            $tmp_option = $this->options['string'];

            if ($tmp_option != $option) {
                $this->options['string'] = array($tmp_option, $option);
            }
        } else {
            $this->options['string'][] = $option;
        }
    }

    function getShipmentValue() {
        return $this->shipmentValue;
    }

    function setShipmentValue($value) {
        if (!$value > 0) {
            throw new Exception('UNSUPPORTED ShipmentValue: [' . $value . '] ShipmentValue must be greater then 0');
        }

        $this->shipmentValue = $value;
        $this->addOrderOption('UBEZP');
    }

    function createShipment($shipmentTypeCode, $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {

        $this->setShipmentTypeCode($shipmentTypeCode);

        $this->dimension1 = $dim1;
        $this->dimension2 = $dim2;
        $this->dimension3 = $dim3;

        $this->weight = $weight;

        if ((300 < (2 * $dim1 + 2 * $dim2 + $dim3) && (2 * $dim1 + 2 * $dim2 + $dim3) < 330) || (32 < $weight) && ($weight < 70)) {
            $this->addOrderOption('PRZES_NIETYP');
        }
        /*
          if(330<(2*$dim1+2*$dim2+$dim3)<419 || $weight >= 70){
          addOrderOption('DUZA_PACZKA');
          }
         */
    }

}