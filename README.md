# 📚 Papelería Todo Arte - E-Commerce

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-323330?style=for-the-badge&logo=javascript&logoColor=F7DF1E)

Este es un sistema de comercio electrónico desarrollado desde cero utilizando la arquitectura **MVC (Modelo-Vista-Controlador)** en PHP puro. Este proyecto fue diseñado como una solución integral para la gestión de inventario, ventas y administración de una papelería, destacando un modelo de negocio basado en reservas online y pagos en punto físico.

Proyecto destacado del portafolio, desarrollado para demostrar competencias sólidas en arquitectura de software, bases de datos relacionales y diseño de interfaces.

## ✨ Características Principales

* **🛒 Carrito de Compras Optimizado:** Implementado con Vanilla JS y `LocalStorage` para reducir la carga del servidor y mejorar la experiencia del usuario.
* **🔎 Búsqueda en Tiempo Real:** Motor de búsqueda con peticiones asíncronas (AJAX / Fetch API) y técnica de *Debouncing* para optimizar recursos.
* **🛡️ Seguridad Robusta:** Prevención de Inyección SQL mediante PDO (Prepared Statements) y cifrado de contraseñas con el algoritmo criptográfico `Bcrypt`.
* **📊 Panel de Administración (Dashboard):** CRUD completo para la gestión de productos, variaciones (ej. tamaños, empaques), cupones de descuento, categorías y roles de usuario (Administrador, Vendedor, Cliente).
* **🧾 Integridad de Datos Contables:** Sistema de *Snapshots* (fotografías de datos en formato JSON) al momento de generar órdenes para mantener el histórico de facturación inmutable.
* **🎨 UI/UX Responsivo:** Interfaz limpia construida con Bootstrap 5 y un sistema de variables CSS (`:root`) para una fácil personalización corporativa (White-label).

## 🛠️ Tecnologías Utilizadas

* **Backend:** PHP (Arquitectura MVC personalizada, PDO).
* **Frontend:** HTML5, CSS3, JavaScript (ES6+).
* **Framework UI:** Bootstrap 5, FontAwesome (Iconos), SwiperJS (Carruseles).
* **Base de Datos:** MySQL (Relacional, motor InnoDB).
* **Interacciones:** SweetAlert2 para notificaciones modales fluidas.

## 🚀 Instalación y Despliegue (Entorno Local)

Sigue estos pasos para probar el proyecto en tu entorno local (ej. XAMPP, WAMP, Laragon):

1. **Clonar el repositorio:**
   ```bash
   git clone [https://github.com/estefanyosorio84-oss/proyecto-adso-2026.git](https://github.com/estefanyosorio84-oss/proyecto-adso-2026.git)
