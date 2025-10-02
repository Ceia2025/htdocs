<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unir PDFs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Unir Archivos PDF</h2>
        <form id="uploadForm" action="merge.php" method="post" enctype="multipart/form-data" class="upload-form">
            <label for="pdfs" class="file-label">Selecciona tus PDFs</label>
            <input type="file" id="pdfs" name="pdfs[]" multiple accept="application/pdf">
            
            <p id="file-count" class="file-info">Ningún archivo seleccionado</p>
            
            <button type="submit" class="btn">Unir PDFs</button>
        </form>
    </div>

    <!-- Modal para mostrar PDF -->
    <?php if (isset($_GET['merged'])): ?>
    <div class="modal" id="pdfModal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('pdfModal').style.display='none'">&times;</span>
            <iframe src="documento_unido.pdf" width="100%" height="500px"></iframe>
        </div>
    </div>
    <script>
        document.getElementById('pdfModal').style.display = 'flex';
    </script>
    <?php endif; ?>

    <script>
        const input = document.getElementById('pdfs');
        const info = document.getElementById('file-count');
        const form = document.getElementById('uploadForm');

        // Mostrar número de archivos seleccionados
        input.addEventListener('change', () => {
            if (input.files.length > 0) {
                info.textContent = `${input.files.length} archivo(s) seleccionado(s): ` +
                    Array.from(input.files).map(f => f.name).join(', ');
            } else {
                info.textContent = "Ningún archivo seleccionado";
            }
        });

        // Validar antes de enviar
        form.addEventListener('submit', (e) => {
            if (input.files.length === 0) {
                e.preventDefault(); // evitar envío
                alert("Por favor selecciona al menos un archivo PDF.");
            }
        });
    </script>
</body>
</html>
