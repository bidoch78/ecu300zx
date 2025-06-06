<?php 

declare(strict_types=1);

use ecu300zx\route;

/* LOGIN & TOKEN */
// route::add("api/user/login", "user@login", null, array("POST"));
// route::add("api/user/logout", "user@logout", array("auth"), array("POST"));

route::add("api/version", "version@getversion", null, array("GET"));
route::add("api/eprom/{class}/{eprom}/{file}/all", "eprom@getall", null, array("GET"));

?>
