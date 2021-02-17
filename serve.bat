::
::==================================================
:: Lunch the local php development server
::==================================================
::
:: @author Alexandr Shamanin (@slpAkkie)
::



@echo off
title PHP Development Server

if "%~1" NEQ "" (set port=%1) else (set port=8000)

start http://localhost:%port%
php -S localhost:%port%
