# 🐔 Granja Salvadoreña API

API REST para un e-commerce de productos salvadoreños, desarrollada con **Laravel 13**, **MySQL**, **JWT**, **Stripe** y **Swagger/OpenAPI**.

El proyecto permite gestionar usuarios, productos, categorías, órdenes de compra y pagos, proporcionando una API segura y documentada para ser consumida por aplicaciones web, móviles u otros clientes.

---

## 🚀 Tecnologías

- PHP 8.2+
- Laravel 13
- MySQL
- JWT Authentication
- Stripe
- Swagger / OpenAPI
- Composer
- Postman / Thunder Client

---

## 📋 Funcionalidades

### 🔐 Autenticación

- Registro de usuarios.
- Inicio de sesión.
- Generación de tokens JWT.
- Consulta del usuario autenticado.
- Cierre de sesión.
- Protección de endpoints mediante JWT.

### 📦 Productos

- Listado de productos.
- Consulta individual.
- Creación de productos.
- Actualización de productos.
- Eliminación de productos.
- Asociación de productos con categorías.
- Validación mediante Form Requests.

### 🗂️ Categorías

- Crear categorías.
- Listar categorías.
- Consultar una categoría.
- Actualizar categorías.
- Eliminar categorías.
- Consultar productos asociados.

### 🛒 Órdenes

- Crear órdenes de compra.
- Agregar productos a una orden.
- Calcular automáticamente los subtotales y el total.
- Consultar historial de órdenes del usuario.
- Consultar una orden específica.
- Cancelar órdenes.
- Validar que una orden pertenezca al usuario autenticado.

### 💳 Pagos

- Integración con Stripe.
- Creación de PaymentIntents.
- Registro de transacciones.
- Relación entre pagos y órdenes.
- Manejo de errores de Stripe.
- Uso de claves de Stripe mediante variables de entorno.

### 📚 Documentación

- Documentación completa mediante Swagger/OpenAPI.
- Swagger UI disponible en `/api/documentation`.

---

## ⚙️ Requisitos

Antes de instalar el proyecto necesitas:

- PHP 8.2 o superior.
- Composer.
- MySQL.
- Git.
- Cuenta de Stripe para utilizar la integración en modo de prueba.

---

## 📥 Instalación

Clonar el repositorio:

```bash
git clone URL_DEL_REPOSITORIO
```

Entrar al proyecto:

```bash
cd granja-salvadorena-api
```

Instalar las dependencias:

```bash
composer install
```

Crear el archivo `.env`.

### Windows

```bash
copy .env.example .env
```

### Linux / macOS

```bash
cp .env.example .env
```

Generar la clave de Laravel:

```bash
php artisan key:generate
```

---

## 🗄️ Configuración de MySQL

Crear una base de datos en MySQL y configurar las siguientes variables en `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_base_datos
DB_USERNAME=root
DB_PASSWORD=
```

Después ejecutar las migraciones:

```bash
php artisan migrate
```

---

## 🌱 Seeders

Para cargar los datos de ejemplo disponibles en el proyecto:

```bash
php artisan db:seed
```

También puede utilizarse:

```bash
php artisan migrate:fresh --seed
```

> `migrate:fresh` elimina las tablas existentes y debe utilizarse únicamente en desarrollo cuando sea seguro eliminar los datos actuales.

---

## 🔐 Configuración JWT

Generar el secreto utilizado para los tokens JWT:

```bash
php artisan jwt:secret
```

El valor será almacenado en:

```env
JWT_SECRET=...
```

El secreto JWT no debe compartirse ni subirse al repositorio.

---

## 💳 Configuración de Stripe

Configurar las claves de Stripe en modo de prueba:

```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_CURRENCY=usd
```

Las claves secretas no deben compartirse ni subirse a GitHub.

La API utiliza Stripe para crear `PaymentIntents` asociados a las órdenes.

---

## ▶️ Ejecutar el proyecto

Iniciar el servidor de desarrollo:

```bash
php artisan serve
```

La API estará disponible normalmente en:

```text
http://127.0.0.1:8000
```

---

## 🔑 Autenticación

Los endpoints protegidos utilizan JWT mediante el header:

```http
Authorization: Bearer TOKEN
```

Primero se debe iniciar sesión:

```http
POST /api/auth/login
```

El token recibido debe utilizarse posteriormente para acceder a los endpoints protegidos.

---

## 🔗 Endpoints principales

### 🔐 Authentication

```text
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout
GET  /api/auth/me
```

### 📦 Products

```text
GET    /api/products
POST   /api/products
GET    /api/products/{product}
PUT    /api/products/{product}
DELETE /api/products/{product}
```

### 🗂️ Categories

```text
GET    /api/categories
POST   /api/categories
GET    /api/categories/{category}
PUT    /api/categories/{category}
DELETE /api/categories/{category}
```

### 🛒 Orders

```text
GET   /api/orders
POST  /api/orders
GET   /api/orders/{order}
PATCH /api/orders/{order}/cancel
```

### 💳 Payments

```text
POST /api/orders/{order}/payment
```

### 🩺 Health Check

```text
GET /api/health
```

---

## 📚 Swagger / OpenAPI

La documentación interactiva de la API está disponible en:

```text
http://127.0.0.1:8000/api/documentation
```

Para regenerar la documentación después de modificar los atributos OpenAPI:

```bash
php artisan l5-swagger:generate
```

Swagger permite consultar los endpoints, parámetros, respuestas y mecanismos de autenticación de la API.

---

## 🧪 Pruebas

La API fue probada utilizando clientes HTTP como **Postman / Thunder Client**.

Se verificaron principalmente:

- Registro y autenticación de usuarios.
- Generación y utilización de JWT.
- CRUD de productos.
- CRUD de categorías.
- Relación entre productos y categorías.
- Creación y consulta de órdenes.
- Cálculo de totales.
- Protección de órdenes entre usuarios.
- Cancelación de órdenes.
- Creación de PaymentIntents mediante Stripe.
- Registro de pagos.
- Respuestas de error HTTP.
- Documentación mediante Swagger.

### Códigos HTTP utilizados

```text
200  Operación exitosa
201  Recurso creado
401  No autenticado
403  Sin permisos
404  Recurso no encontrado
422  Datos inválidos / operación no permitida
500  Error interno
```

---

## 🔒 Seguridad

Las credenciales y secretos se administran mediante variables de entorno.

El archivo:

```text
.env
```

no debe subirse al repositorio.

El proyecto incluye:

```text
.env.example
```

como plantilla de configuración.

Nunca deben incluirse en GitHub:

- Contraseñas de la base de datos.
- `JWT_SECRET`.
- `STRIPE_SECRET`.
- Otras credenciales privadas.

---

## 🗃️ Base de datos

El proyecto utiliza las siguientes tablas principales:

```text
users
products
categories
orders
order_items
payments
```

Relaciones principales:

```text
User
 └── hasMany Orders

Category
 └── hasMany Products

Product
 ├── belongsTo Category
 └── hasMany OrderItems

Order
 ├── belongsTo User
 ├── hasMany OrderItems
 └── hasOne Payment

OrderItem
 ├── belongsTo Order
 └── belongsTo Product

Payment
 └── belongsTo Order
```

---

## 📁 Estructura principal

```text
granja-salvadorena-api/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Requests/
│   │
│   └── Models/
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── routes/
│   └── api.php
│
├── storage/
├── tests/
│
├── .env.example
├── .gitignore
├── artisan
├── composer.json
└── README.md
```

---

## 🔄 Flujo general de la API

```text
Usuario
   │
   ▼
Registro / Login
   │
   ▼
JWT
   │
   ▼
Catálogo de productos
   │
   ▼
Crear orden
   │
   ▼
Calcular total
   │
   ▼
Crear PaymentIntent
   │
   ▼
Stripe
   │
   ▼
Registrar Payment
```

---

## 🛠️ Comandos útiles

Ver las rutas disponibles:

```bash
php artisan route:list
```

Ver el estado de las migraciones:

```bash
php artisan migrate:status
```

Regenerar Swagger:

```bash
php artisan l5-swagger:generate
```

Limpiar la caché de configuración:

```bash
php artisan config:clear
```

Iniciar el servidor:

```bash
php artisan serve
```

---

## 👨‍💻 Autor

**Gerson Rodriguez**

Proyecto desarrollado con Laravel 13 como API REST para un e-commerce de productos salvadoreños (carnes, cremas, quesos, embutidos, pulpas de frutas, etc.).