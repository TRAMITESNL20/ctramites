<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TramitesCampoTramiteRepository;
use App\Entities\TramitesCampoTramite;
use App\Validators\TramitesCampoTramiteValidator;

/**
 * Class TramitesCampoTramiteRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TramitesCampoTramiteRepositoryEloquent extends BaseRepository implements TramitesCampoTramiteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TramitesCampoTramite::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
