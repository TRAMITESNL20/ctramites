<?php 
namespace App\Custom\UpdateTramite;

interface iTramite
{
    public function setTramite($tramite);
    public function getTotalActual();
    public function getDetalleActual();
    public function getParamsParaCosto();
    public function calcularTotal($params);
    public function updateDetalle();

    public function getTotal();

    public function canUpdate();
    public function getDetalleNuevoComoObj();
}