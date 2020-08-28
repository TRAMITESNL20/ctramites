<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TramitesRepository;
use App\Entities\Tramites;
use App\Validators\TramitesValidator;

/**
 * Class TramitesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TramitesRepositoryEloquent extends BaseRepository implements TramitesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tramites::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
