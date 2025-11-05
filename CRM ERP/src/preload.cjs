const { contextBridge, ipcRenderer } = require('electron');

console.log("⚙️ preload.cjs ejecutándose...");

try {
  contextBridge.exposeInMainWorld('api', {
    // 🔹 Productos (CRUD específico)
    list: () => ipcRenderer.invoke('products:list'),
    create: (data) => ipcRenderer.invoke('products:create', data),
    update: (data) => ipcRenderer.invoke('products:update', data),
    remove: (id) => ipcRenderer.invoke('products:delete', id),

    // 🔹 CRUD genérico para todos los demás módulos
    listAll: (collection) => ipcRenderer.invoke('data:list', collection),
    createIn: (collection, item) => ipcRenderer.invoke('data:create', { collection, item }),
    updateIn: (collection, id, item) => ipcRenderer.invoke('data:update', { collection, id, item }),
    removeIn: (collection, id) => ipcRenderer.invoke('data:delete', { collection, id })
  });

  console.log("✅ window.api expuesto correctamente desde preload.cjs");
} catch (err) {
  console.error("❌ Error en preload.cjs:", err);
}
