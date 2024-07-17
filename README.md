# Autor: Isaac Alejandro López Castrejón


# Descripción del Script PHP para Captura y Registro de Datos

Este script PHP está diseñado para recibir datos enviados desde diversas fuentes y métodos, y almacenarlos en un archivo de registro (.log). Además de los datos recibidos, el script también registra el origen, el método de envío y el tipo de datos recibidos. Esta funcionalidad es esencial para el monitoreo y análisis de la información entrante, asegurando una trazabilidad completa y una gestión eficiente de los datos.

Características del Script:

- Captura de Datos: El script es capaz de recibir datos a través de diferentes métodos de envío, como GET, POST, PUT, y DELETE.
- Registro Detallado: Cada entrada en el archivo de registro incluye:
- Origen: La dirección IP o el nombre del host desde donde se envían los datos.
- Método: El método HTTP utilizado para enviar los datos.
- Tipo de Datos: El tipo de datos recibidos (por ejemplo, JSON, XML, texto plano).
- Contenido: Los datos enviados en sí mismos.
- Almacenamiento Seguro: Los datos se almacenan en un archivo .log, asegurando que la información esté disponible para futuras referencias y análisis.

Este script es una herramienta valiosa para cualquier desarrollador que necesite mantener un registro detallado de las interacciones de datos en sus aplicaciones web.
