<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class TramitesTipoCampo.
 *
 * @package namespace App\Entities;
 */
class TramitesTipoCampo extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'id',
        'tipo'
      ];

      protected $table = "tramites_tipo_campos";

}
