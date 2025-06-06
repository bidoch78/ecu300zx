<?php 

declare(strict_types=1);

namespace ecu300zx\controllers;

use ecu300zx\request;
use ecu300zx\middlewares\middleware;

abstract class controller {

    protected ?request $request = null;

    public function __construct(request $request = null) {
        $this->request = $request;
    }

    public function getAuth():middleware {
        return $this->request->getMiddleware("auth");
    }

}

?>