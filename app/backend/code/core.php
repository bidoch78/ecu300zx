<?php 

declare(strict_types=1);

namespace ecu300zx;

use ecu300zx\exceptions\httpException;
use ecu300zx\exceptions\ecu300zxException;
use ecu300zx\exceptions\pageNotFoundException;
// use megaraid\exceptions\authTokenException;
// use megaraid\exceptions\tokenValidatorException;
use ecu300zx\http;
use ecu300zx\route;
use ecu300zx\request;
// use megaraid\wrappers\cliwrappers;
// use megaraid\controllers\sata_PhysicalDrive;

class core {

    private static string $_version = "1.0.271";
// 	public static mixed $wrapper = null;
// 	public static ?sata_PhysicalDrive $sata_devices = null;

// 	public static function loadSataReader(): void {
// 		self::$sata_devices = new sata_PhysicalDrive();
// 	}

// 	public static function loadWrapper(string $name = null): void {
// 		if (!$name) $name = self::env("megaraid_wrapper_from","megaraid");
// 		self::useWrapper(new ('megaraid\\wrappers\\' . $name . 'wrapper')());
// 		self::$wrapper->activateTrace = true;
// 	}

// 	public static function getSataDevices(): null|sata_PhysicalDrive {
// 		return self::$sata_devices;
// 	}

// 	public static function useWrapper(mixed $w): void {
// 		self::$wrapper = $w;
// 	}

// 	public static function getWrapper(): mixed {
// 		return self::$wrapper;
// 	}

    public static function loadRoutes():void {

		foreach(\glob(__DIR__ . "/routes/*.php") as $file) {
			require_once($file);
		}

    }

	public static function getAccessControlAllowOrigin(): string {
		return "*";
	}

	static function parseRawData($addToPOST = false):null|array {

		$rawData = json_decode(file_get_contents("php://input"), true);
		
		if ($rawData === null) return null;
		if ($addToPOST) {
			
			if (is_array($rawData)) {
				$_POST = $_POST + $rawData;
			}
			
		}
		
		return $rawData;
		
	}

	public static function processRequest():void {

		$request = new request();
		$request->loadURLData();

        $routeData = $request->getRouteData();

 		if ($routeData) {
			
			$data = [];

		  	if ($routeData["data"]["methods"]) {
		  		$methods = $routeData["data"]["methods"];
		  		if (!is_array($methods)) $methods = array($methods);
		  		$find = false;
		  		$requestMethod = $_SERVER["REQUEST_METHOD"];
		  		foreach($methods as $method) {
		  			if (strcasecmp($method, $requestMethod) == 0) { $find = true; break; }
		  		}
		  		if (!$find) throw new httpException(401);
		  	}
			
		  	if ($routeData["data"]["middlewares"]) {
				
		        $middlewares = $routeData["data"]["middlewares"];
		  		if (!is_array($middlewares)) $middlewares = array($middlewares);
				
		  		foreach($middlewares as $midware) {

                    $midware = "ecu300zx\\middlewares\\" . $midware;
                    if ($request->getMiddleware($midware)) throw new ecu300zxException("middleware `" . $midware . "` already declared", megaraidException::MIDDLEWARE_ERROR);

                    $mw = new $midware($request);
                    $request->addMiddleware($mw);

		  		}

                //check middlewares
                foreach($request->getMiddlewares() as $mw) {
                    if (!$mw->check()) throw new ecu300zxException("reject by middleware `" . $mw::class . "`", ecu300zxException::MIDDLEWARE_ERROR);
					$data = $mw->prependData($data);
                }

		  	}

			$controller = "ecu300zx\\controllers\\" . $routeData["data"]['class'];
			$method = $routeData["data"]['method']; 
			$ctrl = new $controller($request);
			$data = array_merge($data, $ctrl->$method());
			self::returnJSONData($data);
	
 		}
 		else {
 		  	throw new pageNotFoundException("No route");
 		}
		
	}

	static function returnJSONData($data):void {
		
        header("Content-Type: application/json");

		if (!isset($data["success"])) $data["success"] = 1;

		$data["session"] = session_id();

        echo json_encode($data);
		
	}
	
	static function returnJSONError(\Exception $ex):void {

        header("Content-Type: application/json");

        $error = [ 'success' => 0, 'status' => 500, 'message' => $ex->getMessage() ];

	 	if ($ex instanceof httpException) { 
			$error["status"] = $ex->getHttpError();
			header($ex->getHeader());
		}
	 	else if ($ex instanceof ecu300zxException) $error["status"] = $ex->getCode();
		
	    echo json_encode($error);
		
    }

	static function env(string $name, ?string $default = null): null|string {
		$env = getenv($name);
		return ($env) ? $env : $default;
	}

    static function getVersion() { return self::env("ecu300zx_app_version", self::$_version); }

}

?>