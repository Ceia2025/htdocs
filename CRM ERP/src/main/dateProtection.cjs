const { app, dialog } = require('electron');
const { createWindow } = require('./window.cjs');
require('./crud.cjs'); // registrar CRUD

const startDate = new Date('2025-11-05');
const endDate = new Date('2025-12-12');

async function checkDateAndLaunch(baseDir) {
  const today = new Date();
  if (today < startDate || today > endDate) {
    await app.whenReady();
    dialog.showErrorBox(
      'Acceso no autorizado',
      'Esta aplicación solo puede utilizarse entre el 5 de noviembre y el 12 de diciembre de 2025.\n\n' +
      'Por favor, contacta con Daniel Scarlazzetta para más información.'
    );
    app.quit();
  } else {
    createWindow(baseDir);
  }
}

module.exports = { checkDateAndLaunch };
