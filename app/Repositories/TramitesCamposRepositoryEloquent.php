<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TramitesCamposRepository;
use App\Entities\TramitesCampos;
use App\Validators\TramitesCamposValidator;

/**
 * Class TramitesCamposRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TramitesCamposRepositoryEloquent extends BaseRepository implements TramitesCamposRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TramitesCampos::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
