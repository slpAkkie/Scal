<?php

/**
 * Файл предоставляет функционал для использования namespace'ов в вашем проекте.
 *
 * Вам достаточно просто подключить
 * этот скрипт в ваш файл, являющийся входной точкой в приложение.
 *
 * Файл должен лежать в корне вашего проекта.
 * Все используемые namespace'ы не должны находиться выше этого скрипта в иерархии файлов
 *
 *
 *
 * @version 1.0.0
 * @author Alexandr Shamanin
 */





/**
 * Возьмем данные из JSON файла о namespace'ах
 * и путям к ним
 *
 * @var array
 */
$autoload_data = json_decode(@file_get_contents(__DIR__ . '/.autoload.json'), true);

/**
 * Если чтение файла не удалось,
 * значит никаких специально определенных namespace'ов нет
 * Установим пустой массив
 */
$autoload_data || $autoload_data = array();



/**
 * Функция автозагрузки классов
 * В соответствии с данными из JSON файла
 *
 * @param string $class_path Полный путь к классу, который необходимо подключить
 *
 * @return void
 */
function namespace_autoload(string $class_path)
{
  /**
   * Обозначим переменную $autoload_data как глобальную
   */
  global $autoload_data;



  /**
   * Переберем ключи в массиве namespace'ов
   * проверим на наличие их в подключаемом namespace
   * и подключим его
   */
  foreach ($autoload_data as $namespace => $path) {
    /**
     * Составим регулярное выражение для поиска namespace'а из массива данных
     * в строке - необходимый для подключения класс
     *
     * @var string
     */
    $class_regexp = '/^' . $namespace . '\/';

    /**
     * Если будет найдено соответствие, то заменим его на полный путь
     */
    if (preg_match($class_regexp, $class_path)) {
      $class_path = str_ireplace($namespace, $path, $class_path);
      break;
    }
  }

  /**
   * Подключим соответствующий файл
   */
  include_once(__DIR__ . "\\{$class_path}.php");
}



/**
 * Укажем нашу функцию для того, чтобы она использовалась
 * для подключения классов
 */
spl_autoload_register('namespace_autoload');
