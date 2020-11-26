<?php



/**
 * Запишем класс Bootstrap в namespace Core
 */

namespace Core;



/**
 * Класс предоставляет данные для определения
 * контроллера, метода и параметров, переданных в запросе
 */
class Bootstrap
{
  public function __construct()
  {
    /** Code here... */
    echo 'Вы успешно подключили autoload.php';
    echo '<br>';
    echo 'Класс Bootstrap подключен и успешно работает';
  }
}
