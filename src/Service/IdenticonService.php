<?php

namespace App\Service;

use App\Entity\User;
use RedeyeVentures\GeoPattern\GeoPattern;

class IdenticonService
{

    public function __construct(
        private GeoPattern $geoPattern
    )
    {
    }

    public function createIdenticon(User $user) : string
    {
        $this->geoPattern->setString($user->getEmail());
        $this->geoPattern->setBaseColor('#eeeeee');
        $this->geoPattern->setColor('#167958');
        return $this->geoPattern->toDataURI();
    }

}
