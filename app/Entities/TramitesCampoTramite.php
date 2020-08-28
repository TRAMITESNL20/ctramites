<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TramitesCampoTramite.
 *
 * @package namespace App\Entities;
 */
class TramitesCampoTramite extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'id',
        'id_tramite',
        'id_campo',
        'id_tipo',
        'caracteristicas'
      ];

      protected $table = "tramites_campo_tramites";

}
