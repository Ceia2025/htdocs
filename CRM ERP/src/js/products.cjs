// products.cjs
console.log("📦 Módulo Productos cargado correctamente");

// Funciones auxiliares
function capitalizeWords(str) {
  return str
    .toLowerCase()
    .replace(/\b\w/g, (l) => l.toUpperCase());
}

function upperCaseSKU(sku) {
  return sku.toUpperCase();
}

const statusDiv = document.getElementById('status');

// Mostrar mensajes visuales
function showMessage(msg, ok = true) {
  statusDiv.textContent = msg;
  statusDiv.style.color = ok ? "green" : "red";
}

// Renderizado de tabla
async function render() {
  const data = await window.api.list();
  const tbody = document.getElementById('tbody');
  tbody.innerHTML = '';

  data.forEach(p => {
    const tr = document.createElement('tr');
    const fecha = p.fecha ? new Date(p.fecha).toLocaleDateString() : '-';
    tr.innerHTML = `
      <td>${p.id}</td>
      <td>${p.name}</td>
      <td>${p.sku}</td>
      <td>${p.price}</td>
      <td>${p.qty}</td>
      <td>${fecha}</td>
      <td>
        <button onclick="edit(${p.id})">Editar</button>
        <button onclick="remove(${p.id})">Eliminar</button>
      </td>`;
    tbody.appendChild(tr);
  });
}

// Eliminar producto
async function remove(id) {
  if (confirm('¿Eliminar producto?')) {
    const res = await window.api.remove(id);
    showMessage(res.message || "Eliminado correctamente");
    render();
  }
}

// Editar producto
async function edit(id) {
  const data = await window.api.list();
  const p = data.find(x => x.id === id);
  if (!p) return;
  document.getElementById('id').value = p.id;
  document.getElementById('name').value = p.name;
  document.getElementById('sku').value = p.sku;
  document.getElementById('price').value = p.price;
  document.getElementById('qty').value = p.qty;
}

// Guardar producto
document.getElementById('form').addEventListener('submit', async e => {
  e.preventDefault();
  const id = document.getElementById('id').value;

  const prod = {
    id: id ? parseInt(id) : undefined,
    name: capitalizeWords(document.getElementById('name').value.trim()),
    sku: upperCaseSKU(document.getElementById('sku').value.trim()),
    price: parseFloat(document.getElementById('price').value),
    qty: parseInt(document.getElementById('qty').value),
    fecha: new Date().toISOString()
  };

  let res = id ? await window.api.update(prod) : await window.api.create(prod);
  showMessage(res.message, res.ok);
  e.target.reset();
  render();
});

// Inicializar
render();
