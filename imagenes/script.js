let indiceActual = 0;
let imagenes = [];

function abrirModal(index) {
    indiceActual = index;
    mostrarImagen();
    document.getElementById('modal').style.display = 'flex';
}

function cerrarModal() {
    document.getElementById('modal').style.display = 'none';
}

function cambiarImagen(direccion) {
    indiceActual += direccion;
    if (indiceActual < 0) indiceActual = imagenes.length - 1;
    if (indiceActual >= imagenes.length) indiceActual = 0;
    mostrarImagen();
}

function mostrarImagen() {
    const img = document.getElementById('imagenAmpliada');
    const link = document.getElementById('descargarModal');
    img.src = imagenes[indiceActual];
    link.href = imagenes[indiceActual];
}

document.addEventListener('keydown', e => {
    if (document.getElementById('modal').style.display !== 'flex') return;
    if (e.key === 'ArrowRight') cambiarImagen(1);
    if (e.key === 'ArrowLeft') cambiarImagen(-1);
    if (e.key === 'Escape') cerrarModal();
});

// === Lazy load real ===
document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img.lazy');
    const options = { rootMargin: '100px' };

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.onload = () => img.classList.add('loaded');
                obs.unobserve(img);
            }
        });
    }, options);

    lazyImages.forEach(img => observer.observe(img));
    imagenes = Array.from(lazyImages).map(img => img.dataset.full);
});