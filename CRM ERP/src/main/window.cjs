const { BrowserWindow } = require('electron');
const path = require('path');

function createWindow(baseDir) {
  const preloadPath = path.join(baseDir, 'preload.cjs');

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

 win.loadFile(path.join(baseDir, '..', 'renderer', 'index.html'));

  win.webContents.on('did-finish-load', () => {
    console.log('✅ Ventana cargada correctamente');
  });

  return win;
}

module.exports = { createWindow };
