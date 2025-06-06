<?php

declare(strict_types=1);

namespace ecu300zx\exceptions;

class ecu300zxException extends \Exception {

    const LOGIN_FAILED = 1010;
    const MIDDLEWARE_ERROR = 910;
    const TOKEN_GENERATOR = 920;
    const TOKEN_VALIDATOR = 921;
        
}

?>