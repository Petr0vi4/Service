<?php
namespace Phasty\Service {

    use Phasty\Service\Exceptions;

    /**
     * Class Error
     * Класс, для критической ошибки
     *
     * @package Phasty\Service
     */
    abstract class Error extends \Exception {

        public function __construct($message = "") {
            return parent::__construct($message, static::getErrorCode());
        }

        /**
         * Возвращает код ошибки для класса.
         * Метод должен быть переопределен в наследнике, для того, чтобы
         * каждое исключение генерировало уникальный код.
         *
         * @return int код ошибки
         */
        protected static function getErrorCode() {
            return Exceptions::INTERNAL_ERROR;
        }

        /**
         * Возвращает статус http-ответа для данного вида ошибки
         *
         * @return int статус http-ответа
         */
        public function getHttpStatus() {
            return 500;
        }
    }
}