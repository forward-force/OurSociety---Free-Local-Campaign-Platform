<?php
declare(strict_types = 1);

namespace OurSociety\Model\Table\Finder\Users;

use Cake\ORM\Query;
use OurSociety\Model\Table\Finder\Finder;

class IsCommunityContributorFinder extends Finder
{
    /**
     * {@inheritdoc}. Custom finder to grab the current mayor.
     */
    public function __invoke(Query $query, array $options = []): Query
    {
        return $query->where([
            $this->aliasField($query, 'community_contributor') => true,
        ]);
    }
}
