@echo off

set PHP_EXE=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe
set PROJECT_DIR=C:\laragon\www\ClickSpace

cd /d "%PROJECT_DIR%"
"%PHP_EXE%" "%PROJECT_DIR%\scheduled_backup.php"
