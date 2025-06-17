<?php 

declare(strict_types=1);

namespace ecu300zx\wrappers;

use ecu300zx\wrappers\epromData;
use ecu300zx\wrappers\epromWrapper;

abstract class epromText extends epromData {

   function __construct(string $name, epromWrapper $eprom) {
      parent::__construct($name, $eprom);
   }

   function getType(): string {
      return "text";
   }

}

?>