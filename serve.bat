@echo off
chcp 65001 > nul

start http://localhost:8000
php -S localhost:8000

echo Работа сервера была завершена
pause