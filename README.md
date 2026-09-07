# AirSense CEFA 🌿

![Logo AirSense](ruta/al/logo.png)

AirSense CEFA es un sistema de monitoreo de calidad del aire basado en Internet de las Cosas (IoT) desarrollado para el Centro de Formación Agroindustrial La Angostura - Regional Huila. 🏫

## 📝 Descripción

Este proyecto tiene como objetivo monitorear y analizar en tiempo real las variables ambientales (CO2, temperatura, humedad) en los diferentes ambientes de formación del CEFA, utilizando una red de sensores IoT. La plataforma web permite visualizar los datos, generar alertas, predecir situaciones de riesgo y sugerir acciones preventivas para garantizar la salud y el bienestar de la comunidad educativa. 🌡️💨

## 🚀 Funcionalidades principales

- 📈 Monitoreo en tiempo real de CO2, temperatura y humedad
- 🚦 Visualización del estado ambiental mediante un sistema de semáforo
- 🔔 Alertas visuales y por correo cuando se superan umbrales críticos
- 🧠 Módulo de Inteligencia Artificial para análisis predictivo
- 📊 Reportes y gráficas históricas de las variables monitoreadas
- 🔒 Gestión de usuarios y roles con autenticación segura
- 🌐 Acceso remoto desde cualquier dispositivo con internet
- 📱 Diseño responsive para visualización en smartphones y tablets

## 🛠️ Arquitectura y tecnologías utilizadas

- **Sensores:** Nodos ESP32 con sensores MH-Z19B (CO2), DHT22 (temperatura y humedad)
- **Conectividad:** Wi-Fi (IEEE 802.11 b/g/n)
- **Protocolos:** MQTT para comunicación entre nodos y servidor
- **Backend:** Node.js con Express.js y MongoDB
- **Frontend:** React.js con Redux y Material-UI
- **Análisis de datos:** Python con NumPy, Pandas y Scikit-Learn
- **Despliegue:** Contenedores Docker en AWS EC2
- **Integración y entrega continua (CI/CD):** Jenkins y GitHub Actions

## 📁 Estructura del repositorio

- `./frontend` - Código fuente del frontend en React.js
- `./backend` - Código fuente del backend en Node.js
- `./data_analysis` - Scripts de análisis de datos y módulo de IA en Python
- `./hardware` - Esquemáticos y firmware de los nodos ESP32
- `./docs` - Documentación del proyecto
