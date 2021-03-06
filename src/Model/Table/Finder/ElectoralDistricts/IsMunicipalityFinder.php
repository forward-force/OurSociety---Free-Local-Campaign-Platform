<?php
declare(strict_types=1);

namespace OurSociety\Model\Table\Finder\ElectoralDistricts;

use Cake\ORM\Query;
use OurSociety\Model\Table\Finder\Finder;

class IsMunicipalityFinder extends Finder
{
    public const ID_VIP_MUNICIPALITY = 'municipality';

    public function __invoke(Query $query, array $options = []): Query
    {
        return $query->matching('DistrictTypes', function (Query $query) {
            return $query->where([
                $this->aliasField($query, 'id_vip') => self::ID_VIP_MUNICIPALITY,
            ]);
        });
    }
}
