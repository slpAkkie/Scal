::
::==================================================
:: Lunch the local php development server
::==================================================
::
:: @author Alexandr Shamanin (@slpAkkie)
::





@echo off
setlocal EnableDelayedExpansion
chcp 65001 > nul

title PHP Development Server

:: Default values
set port=8000
set openBrowser=false



:checkParameter
set setPort=false

:: If need to set another port different from defalut
if "%~1" EQU "-P" set setPort=true
if "%~1" EQU "--port" set setPort=true

if %setPort% EQU true if "%~2" NEQ "" set port=%2

:: If need to open browser with localhost tab
if "%~1" EQU "-O" set openBrowser=true
if "%~1" EQU "--open" set openBrowser=true

:: Make shift to look next parameter
shift
:: If we have one more parameter let's check it
if "%~1" NEQ "" goto checkParameter



:: Open browser if need and start the server
echo Сервер запущен на порту %port%
if %openBrowser% EQU true start http://localhost:%port%
php -S localhost:%port%
