<?php
namespace Phasty\Service {
    class Router {

        protected static function notImplemented() {
            http_response_code(501);
            die(json_encode([ "message" => "api class not implemented" ]));
        }

        protected static function getClassAndMethod(array $classMappings) {
            if (empty($classMappings)) {
                static::notImplemented();
            }
            $requestedUri = $_SERVER[ "PHP_SELF" ];
            $methodSeparatorPos = strrpos($requestedUri, "/");
            if (empty($methodSeparatorPos)) {
                static::notImplemented();
            }
            $method = substr($requestedUri, $methodSeparatorPos + 1);
            $apiAndClassKey = substr($requestedUri, 0, $methodSeparatorPos);
            $class = null;
            foreach ($classMappings as $api => $apiClassMappings) {
                if (substr($apiAndClassKey, 0, strlen($api)) == $api) {
                    $classKey = substr($apiAndClassKey, strlen($api));
                    if (isset($apiClassMappings[ $classKey ])) {
                        $class = $apiClassMappings[ $classKey ];
                    }
                    break;
                }
            }
            if (is_null($class)) {
                static::notImplemented();
            }
            return [ $class, $method ];
        }

        protected static function findAndCheckInstance($class, $method) {
            if (!class_exists($class, true) || !is_callable([ $class, $method ])) {
                static::notImplemented();
            }

            $instance = new $class;

            if (!$instance instanceof IService) {
                static::notImplemented();
            }
            return $instance;
        }

        protected static function callInstance(IService $instance, $method, array $exceptionMappings = []) {
            try {
                $result = json_encode([ "result" => $instance->$method((new Input)->getData()) ]);
                header("Content-Length: " . strlen($result));
                echo $result;
            } catch (\Exception $exception) {
                $exceptionClass = get_class($exception);
                $httpCode = isset($exceptionMappings[ $exceptionClass ]) ? $exceptionMappings[ $exceptionClass ] : 500;
                $instance->fail($httpCode, $exception->getMessage());
            }
        }

        final public static function route(array $classMappings, array $exceptionMappings = []) {
            header("Content-Type: application/json");
            list($class, $method) = static::getClassAndMethod($classMappings);
            $instance = static::findAndCheckInstance($class, $method);
            static::callInstance($instance, $method, $exceptionMappings);
        }

    }
}
