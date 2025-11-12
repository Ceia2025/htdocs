const { ipcMain} = require('electron');
const { loadDB, saveDB } = require('./db.cjs');

// -------------------- CRUD Productos --------------------
ipcMain.handle('products:list', () => loadDB().products);

ipcMain.handle('products:create', (event, prod) => {
  const db = loadDB();
  const id = db.products.length ? Math.max(...db.products.map(p => p.id || 0)) + 1 : 1;
  const newProd = { id, ...prod };
  db.products.push(newProd);
  saveDB(db);
  return { ok: true, message: 'Producto guardado con éxito', product: newProd };
});

ipcMain.handle('products:update', (event, prod) => {
  const db = loadDB();
  const idx = db.products.findIndex(p => p.id === prod.id);
  if (idx !== -1) {
    db.products[idx] = prod;
    saveDB(db);
    return { ok: true, message: 'Producto actualizado' };
  }
  return { ok: false, message: 'Producto no encontrado' };
});

ipcMain.handle('products:delete', (event, id) => {
  const db = loadDB();
  db.products = db.products.filter(p => p.id !== id);
  saveDB(db);
  return { ok: true, message: 'Producto eliminado' };
});

// -------------------- CRUD genérico --------------------
ipcMain.handle('data:list', (e, collection) => loadDB()[collection] || []);

ipcMain.handle('data:create', (e, { collection, item }) => {
  const db = loadDB();
  if (!db[collection]) db[collection] = [];
  const id = db[collection].length ? Math.max(...db[collection].map(x => x.id || 0)) + 1 : 1;
  db[collection].push({ id, ...item });
  saveDB(db);
  return { ok: true, message: `Guardado en ${collection}` };
});

ipcMain.handle('data:update', (e, { collection, id, item }) => {
  const db = loadDB();
  const idx = db[collection]?.findIndex(x => x.id === id);
  if (idx !== -1) {
    db[collection][idx] = { ...db[collection][idx], ...item };
    saveDB(db);
    return { ok: true };
  }
  return { ok: false, message: 'Elemento no encontrado' };
});

ipcMain.handle('data:delete', (e, { collection, id }) => {
  const db = loadDB();
  if (db[collection]) {
    db[collection] = db[collection].filter(x => x.id !== id);
    saveDB(db);
  }
  return { ok: true };
});
