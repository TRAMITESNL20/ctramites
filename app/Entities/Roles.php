<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Roles.
 *
 * @package namespace App\Entities;
 */
class Roles extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $connection = "db_operacion";

     protected $fillable = ['id','descripcion','tramites','status'];

    protected $table = "oper_roles";

}
