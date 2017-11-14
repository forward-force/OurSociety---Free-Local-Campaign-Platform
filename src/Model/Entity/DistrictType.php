<?php
declare(strict_types=1);

namespace OurSociety\Model\Entity;

/**
 * DistrictType Entity
 *
 * @property string $id
 * @property string $id_vip
 * @property string $id_gapi_scope
 * @property string $name
 * @property string $description
 *
 * @property \OurSociety\Model\Entity\ElectoralDistrict[] $electoral_districts
 */
class DistrictType extends AppEntity
{
    public function isMunicipality()
    {
        return $this->id_vip === 'municipality';
    }

    public function getIcon(): string
    {
        return 'map-signs';
    }
}
