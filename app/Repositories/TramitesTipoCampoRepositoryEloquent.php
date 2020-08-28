<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TramitesTipoCampoRepository;
use App\Entities\TramitesTipoCampo;
use App\Validators\TramitesTipoCampoValidator;

/**
 * Class TramitesTipoCampoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TramitesTipoCampoRepositoryEloquent extends BaseRepository implements TramitesTipoCampoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return TramitesTipoCampo::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
