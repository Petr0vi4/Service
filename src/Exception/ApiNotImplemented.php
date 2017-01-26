<?php
namespace Phasty\Service\Exception {

    use Phasty\Service\Exceptions;
    use Phasty\Service\Error;

    /**
     * Class ApiNotImplemented
     * Ошибка, возникающая при обращении на неизвестный ресурс
     *
     * @package Phasty\Service
     */
    final class ApiNotImplemented extends Error {

        protected static function getErrorCode() {
            return Exceptions::API_NOT_IMPLEMENTED;
        }

    }
}