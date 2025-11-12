import { app, BrowserWindow, ipcMain } from 'electron';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

// 🧠 Obtener __dirname (necesario en ESM)
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// =============================================================
// 📦 CONFIGURAR RUTA DE ALMACENAMIENTO DE DATOS
// =============================================================

// Usamos la ruta segura del usuario (para que funcione en producción)
const userDataPath = app.getPath('userData');
const dataDir = path.join(userDataPath, 'storage');

// Crear carpeta si no existe
if (!fs.existsSync(dataDir)) fs.mkdirSync(dataDir, { recursive: true });

// Ruta del archivo de base de datos JSON
const dbFile = path.join(dataDir, 'data.json');

// Inicializar base de datos si no existe
if (!fs.existsSync(dbFile)) {
  fs.writeFileSync(
    dbFile,
    JSON.stringify(
      {
        products: [],
        customers: [],
        sales: [],
        crm: [],
        rental: [],
        pos: [],
        finance: [],
        services: []
      },
      null,
      2
    )
  );
}

console.log('📄 Base de datos:', dbFile);

// =============================================================
// FUNCIONES DE LECTURA Y GUARDADO
// =============================================================

function loadDB() {
  try {
    return JSON.parse(fs.readFileSync(dbFile, 'utf8'));
  } catch (err) {
    console.error('❌ Error al leer data.json:', err);
    return {
      products: [],
      customers: [],
      sales: [],
      crm: [],
      rental: [],
      pos: [],
      finance: [],
      services: []
    };
  }
}

function saveDB(db) {
  fs.writeFileSync(dbFile, JSON.stringify(db, null, 2), 'utf8');
}

// =============================================================
// BLOQUE 1: CRUD exclusivo para PRODUCTOS
// =============================================================

ipcMain.handle('products:list', () => loadDB().products);

ipcMain.handle('products:create', (event, prod) => {
  const db = loadDB();
  const id = db.products.length ? Math.max(...db.products.map((p) => p.id || 0)) + 1 : 1;
  const newProd = { id, ...prod };
  db.products.push(newProd);
  saveDB(db);
  console.log("✅ Producto guardado:", newProd);
  return { ok: true, message: "Producto guardado con éxito", product: newProd };
});

ipcMain.handle('products:update', (event, prod) => {
  const db = loadDB();
  const idx = db.products.findIndex((p) => p.id === prod.id);
  if (idx !== -1) {
    db.products[idx] = prod;
    saveDB(db);
    return { ok: true, message: 'Producto actualizado' };
  }
  return { ok: false, message: 'Producto no encontrado' };
});

ipcMain.handle('products:delete', (event, id) => {
  const db = loadDB();
  db.products = db.products.filter((p) => p.id !== id);
  saveDB(db);
  return { ok: true, message: 'Producto eliminado' };
});

// =============================================================
// BLOQUE 2: CRUD genérico para todos los demás módulos
// =============================================================

ipcMain.handle('data:list', (e, collection) => {
  const db = loadDB();
  if (!db[collection]) db[collection] = [];
  return db[collection];
});

ipcMain.handle('data:create', (e, { collection, item }) => {
  const db = loadDB();
  if (!db[collection]) db[collection] = [];
  const id = db[collection].length ? Math.max(...db[collection].map((x) => x.id || 0)) + 1 : 1;
  db[collection].push({ id, ...item });
  saveDB(db);
  console.log(`✅ Registro guardado en ${collection}:`, item);
  return { ok: true, message: `Guardado en ${collection}` };
});

ipcMain.handle('data:update', (e, { collection, id, item }) => {
  const db = loadDB();
  if (!db[collection]) db[collection] = [];
  const idx = db[collection].findIndex((x) => x.id === id);
  if (idx !== -1) {
    db[collection][idx] = { ...db[collection][idx], ...item };
    saveDB(db);
    return { ok: true };
  }
  return { ok: false, message: 'Elemento no encontrado' };
});

ipcMain.handle('data:delete', (e, { collection, id }) => {
  const db = loadDB();
  if (!db[collection]) db[collection] = [];
  db[collection] = db[collection].filter((x) => x.id !== id);
  saveDB(db);
  return { ok: true };
});

// =============================================================
// 🕒 CONTROL DE FECHAS DE USO (protección temporal)
// =============================================================

const startDate = new Date("2025-11-05");
const endDate = new Date("2025-12-12");
const today = new Date();

async function checkDateAndLaunch() {
  if (today < startDate || today > endDate) {
    console.log("⛔ Esta aplicación solo puede usarse entre el 05/11/2025 y el 12/12/2025.");
    await app.whenReady();
    const { dialog } = await import('electron');
    dialog.showErrorBox(
      "Acceso no autorizado",
      "Esta aplicación solo puede utilizarse entre el 5 de noviembre y el 12 de diciembre de 2025.\n\n" +
      "Por favor, contacta con Daniel Scarlazzetta para más información."
    );
    app.quit();
    return;
  }
  createWindow();
}

// =============================================================
// 🪟 CREAR VENTANA PRINCIPAL
// =============================================================

function createWindow() {
  const preloadPath = path.join(__dirname, 'preload.cjs');
  console.log('🧩 Cargando preload desde:', preloadPath);

  const win = new BrowserWindow({
    width: 1100,
    height: 800,
    webPreferences: {
      preload: preloadPath,
      contextIsolation: true,
      nodeIntegration: false,
      sandbox: false
    }
  });

  win.webContents.on('did-finish-load', () => {
    console.log('✅ Ventana cargada correctamente');
  });

  win.loadFile(path.join(__dirname, 'renderer', 'index.html'));
}

// =============================================================
// 🚀 EJECUCIÓN PRINCIPAL
// =============================================================

app.whenReady().then(checkDateAndLaunch);

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});
