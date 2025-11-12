const fs = require('fs');
const path = require('path');
const { app } = require('electron');

// 📦 Configurar ruta de almacenamiento
const userDataPath = app.getPath('userData');
const dataDir = path.join(userDataPath, 'storage');
if (!fs.existsSync(dataDir)) fs.mkdirSync(dataDir, { recursive: true });

const dbFile = path.join(dataDir, 'data.json');

// 📄 Inicializar base de datos si no existe
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

module.exports = { loadDB, saveDB };
