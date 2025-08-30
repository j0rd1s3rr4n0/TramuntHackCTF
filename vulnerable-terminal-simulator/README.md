# Vulnerable Terminal Simulator

Este proyecto simula una terminal vulnerable, permitiendo a los usuarios escalar privilegios de manera realista, similar a una auditoría de seguridad. La simulación está diseñada para ayudar a los usuarios a entender y practicar la escalada de privilegios en un entorno controlado.

## Estructura del Proyecto

El proyecto está organizado de la siguiente manera:

```
vulnerable-terminal-simulator
├── src
│   ├── main.py                  # Punto de entrada de la aplicación.
│   ├── terminal
│   │   ├── __init__.py          # Inicializa el paquete de la terminal.
│   │   ├── commands.py          # Define los comandos disponibles en la terminal.
│   │   ├── privilege_escalation.py # Maneja la lógica de escalada de privilegios.
│   │   └── utils.py             # Funciones utilitarias para la terminal.
│   └── tests
│       ├── __init__.py          # Inicializa el paquete de pruebas.
│       └── test_terminal.py      # Pruebas unitarias para los módulos de la terminal.
├── requirements.txt              # Dependencias necesarias para el proyecto.
├── .gitignore                    # Archivos y directorios a ignorar por el control de versiones.
└── README.md                     # Documentación del proyecto.
```

## Instalación

Para instalar las dependencias necesarias, asegúrate de tener `pip` instalado y ejecuta el siguiente comando en la raíz del proyecto:

```
pip install -r requirements.txt
```

## Uso

Para iniciar la simulación de la terminal vulnerable, ejecuta el siguiente comando:

```
python src/main.py
```

Esto iniciará la terminal y te permitirá interactuar con ella, ejecutando comandos y tratando de escalar privilegios.

## Contribuciones

Las contribuciones son bienvenidas. Si deseas contribuir a este proyecto, por favor abre un issue o envía un pull request.

## Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.