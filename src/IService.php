<?php
namespace Phasty\Service {
    interface IService {

        function fail($code, $httpMessage, $message);

    }
}
