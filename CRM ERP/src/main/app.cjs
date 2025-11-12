const { app } = require('electron');
const path = require('path');
const { fileURLToPath } = require('url');
const { checkDateAndLaunch } = require('./dateProtection.cjs');



app.whenReady().then(() => checkDateAndLaunch(__dirname));

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') app.quit();
});
