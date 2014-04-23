<?php

namespace Ekologia\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EkologiaUserBundle extends Bundle
{
    public function getParent() {
        return 'FOSUserBundle';
    }
}
