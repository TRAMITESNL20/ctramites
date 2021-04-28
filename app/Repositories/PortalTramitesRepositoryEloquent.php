<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\PortalTramitesRepository;
use App\Entities\PortalTramites;
use App\Validators\PortalTramitesValidator;

/**
 * Class PortalTramitesRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class PortalTramitesRepositoryEloquent extends BaseRepository implements PortalTramitesRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return PortalTramites::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
