<?php

declare(strict_types=1);

namespace ecu300zx\exceptions;

use \ecu300zx\exceptions\httpException;

class authTokenException extends httpException {

    public function __construct($message = "", int $code = 0, ?\Throwable $previous = null) {
        parent::__construct(498, $message, $code, $previous);
    }

}

?>