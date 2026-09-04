# Cardify — Panel administrativo y API

**Cardify** es una empresa ficticia dedicada a la venta de gift cards digitales a
través de una plataforma web. Nuestro objetivo es brindar a los usuarios una
experiencia rápida y sencilla para adquirir y regalar códigos digitales de sus
tiendas favoritas.

Este repositorio contiene el **backend**: la API que consume la tienda y el
**panel administrativo** para gestionar catálogo, categorías, órdenes, descuentos
y promociones.

> La tienda para clientes vive en un repositorio aparte: **Cardify-Frontend**.

## 💼 Empresa ficticia

| | |
|---|---|
| **Nombre** | Cardify |
| **Descripción** | Plataforma de e-commerce para compra y gestión de gift cards digitales. |
| **Industria** | Tecnología / Comercio electrónico |
| **Ubicación** | Argentina |

## 👥 Integrantes de la comisión

- **Alejo Maximiliano Gonzalez**
- **Gabriel Federico Jose Gimenez Miguel**

## 🧱 Tecnologías

- **Laravel 12** (PHP) para la API y el panel administrativo
- **Blade + Tailwind CSS** para las vistas del panel
- **PostgreSQL** como base de datos

## 🔌 Servicios que integra

- **Mercado Pago** — cobro de las compras y aviso automático cuando se acredita el pago.
- **Cloudinary** — almacenamiento y entrega de las imágenes de las gift cards.
- **Correo electrónico** — envío de los códigos comprados y del enlace para recuperar la contraseña.
- **Notificaciones push** — avisos de compra confirmada, descuentos y promociones.

## 🚀 Puesta en marcha (desarrollo)

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

composer dev
```

El panel queda en `http://localhost:8000` y la API bajo `http://localhost:8000/apis`.

Las credenciales de los servicios externos y la URL de la tienda se cargan en el
archivo `.env` (partiendo de `.env.example`).

### Seeders

```bash
php artisan db:seed                 # ejecuta los seeders
php artisan migrate:fresh --seed    # reinicia la base y vuelve a sembrar
```

**Usuario administrador de prueba**

| Email | Contraseña |
|---|---|
| `juan@example.com` | `secreto123` |

## 📁 Estructura

```
app/
  Http/Controllers/        Panel administrativo
  Http/Controllers/Api/    API de la tienda
  Models/                  GiftCard, Category, Cart, Order, ...
  Services/                Pagos y notificaciones push
resources/views/           Vistas del panel + plantillas de PDF y de mail
routes/                    web.php (panel) · api.php (API, prefijo /apis)
database/migrations/       Esquema de la base
```

## ☁️ Despliegue

- Base de datos PostgreSQL en **Neon** y hosting en **Vercel**.
- Tras cada despliegue con cambios de esquema, las migraciones se corren a mano
  con `php artisan migrate`.
