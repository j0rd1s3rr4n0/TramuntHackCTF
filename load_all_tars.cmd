@echo off
setlocal EnableDelayedExpansion

REM Loads all .tar images from .\tar and runs each on a host port.
REM Default container port is 80, with per-image overrides below.

set "TAR_DIR=%~dp0tar"
set "BASE_PORT=8001"
set "DEFAULT_CONTAINER_PORT=80"
set "PORTS_JSON=%~dp0ports.json"

if not exist "%TAR_DIR%" (
  echo ERROR: No existe la carpeta "%TAR_DIR%".
  echo Crea la carpeta "tar" junto a este .cmd y coloca ahi los .tar
  exit /b 1
)

echo Archivos .tar detectados en "%TAR_DIR%":
dir /b "%TAR_DIR%\*.tar"

for %%F in ("%TAR_DIR%\*.tar") do (
  echo.
  echo Cargando %%~nxF ...
  set "SKIP=0"
  set "IMAGE="
  set "LOAD_LINE="
  for /f "usebackq tokens=*" %%L in (`docker load -i "%%~fF" ^| findstr /c:"Loaded image:"`) do set "LOAD_LINE=%%L"
  if not defined LOAD_LINE (
    echo ERROR: No se pudo leer el nombre de imagen para %%~nxF
    echo Saltando %%~nxF
    set "SKIP=1"
  )
  REM Example line: Loaded image: repo/name:tag
  if "!SKIP!"=="0" (
    for /f "tokens=3" %%I in ("!LOAD_LINE!") do set "IMAGE=%%I"
    if "!IMAGE!"=="" (
      echo ERROR: No se pudo detectar el nombre de la imagen para %%~nxF
      echo Saltando %%~nxF
      set "SKIP=1"
    )
  )

  if "!SKIP!"=="0" (
  set "NAME=%%~nF"
  set "CONTAINER_PORT="
  set "HOST_PORT="

  set "PORT_ARGS="
  if exist "!PORTS_JSON!" (
    for /f "usebackq tokens=*" %%P in (`powershell -NoProfile -Command ^
      "$p=Get-Content -Raw '%PORTS_JSON%' ^| ConvertFrom-Json; " ^
      "$n='%%~nF'; " ^
      "$o=$p.overrides.$n; " ^
      "$d=$p.default; " ^
      "if($o){" ^
      "  if($o -is [System.Collections.IEnumerable] -and -not ($o -is [string])){" ^
      "    $args=''; foreach^($item in $o^){ $args+='-p 0.0.0.0:'+$item.host+':'+$item.container+' '; } $args.Trim()" ^
      "  } else {" ^
      "    '-p 0.0.0.0:'+$o.host+':'+$o.container" ^
      "  }" ^
      "} elseif($d){" ^
      "  '-p 0.0.0.0:'+$d.host+':'+$d.container" ^
      "} else { '' }" ^
    `) do set "PORT_ARGS=%%P"
  )

  if not defined PORT_ARGS (
    set /a HOST_PORT=%BASE_PORT%
    set /a BASE_PORT+=1
    set "PORT_ARGS=-p 0.0.0.0:%HOST_PORT%:%DEFAULT_CONTAINER_PORT%"
  )

  REM Remove existing container with same name to avoid conflicts
    docker rm -f "!NAME!" >nul 2>&1

  echo Ejecutando !IMAGE! con puertos: !PORT_ARGS! ...
  docker run -d --name "!NAME!" !PORT_ARGS! !IMAGE! >nul
    if errorlevel 1 (
      echo ERROR: Fallo al ejecutar !IMAGE! (nombre contenedor: !NAME!)
      echo Saltando %%~nxF
      set "SKIP=1"
    )
  )
  if "!SKIP!"=="0" (
    echo OK: contenedor !NAME!
  )
)

echo.
echo Listo.
exit /b 0
