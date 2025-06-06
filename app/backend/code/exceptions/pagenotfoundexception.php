<?php

declare(strict_types=1);

namespace ecu300zxException\exceptions;

use ecu300zxException\exceptions\httpException;

class pageNotFoundException extends httpException {

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null) {
        parent::__construct(404, $message, $code, $previous);
    }

}

?>