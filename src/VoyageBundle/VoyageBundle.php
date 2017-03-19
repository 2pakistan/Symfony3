<?php

namespace VoyageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class VoyageBundle extends Bundle
{

    public function getParent()
    {
        return 'FOSUserBundle';
    }

}
