# Odoo Lite Electron TXT (sin CSS, sin DB)

App de escritorio con **Electron** que guarda todo en **.txt** (JSON) en `storage/` junto a la app.
Módulos: Productos, Clientes, CRM (leads), Ventas, Arriendo, POS, Finanzas, Servicios.

## Requisitos
- Node.js 18+
- npm

## Desarrollo
```bash
npm install
npm start
```

## Construir ejecutable
```bash
npm run dist
```
- Windows: .exe (NSIS) y Portable
- Linux: AppImage
- macOS: DMG

## Datos
- En dev: `./storage/*.txt`
- En producción: `resources/storage/*.txt` (se empaqueta con la app).

Sin CSS para que lo estilices luego.
