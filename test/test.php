<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Análisis de Anotaciones Estudiantiles - Sistema de Alertas</title> <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-dt@1.13.4/css/jquery.dataTables.min.css">
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.4/js/jquery.dataTables.min.js"></script>
    <style>
        :root {
            --color-danger: #dc2626;
            --color-warning: #d97706;
            --color-success: #059669;
            --color-info: #0284c7;
            --color-neutral: #6b7280;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        .alert-high-risk {
            background: linear-gradient(135deg, #fecaca, #fee2e2);
            border-left: 4px solid var(--color-danger);
            color: #7f1d1d;
        }

        .alert-medium-risk {
            background: linear-gradient(135deg, #fed7aa, #fef3c7);
            border-left: 4px solid var(--color-warning);
            color: #92400e;
        }

        .alert-positive {
            background: linear-gradient(135deg, #bbf7d0, #dcfce7);
            border-left: 4px solid var(--color-success);
            color: #14532d;
        }

        .kpi-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid transparent;
        }

        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .kpi-danger {
            border-left-color: var(--color-danger);
        }

        .kpi-warning {
            border-left-color: var(--color-warning);
        }

        .kpi-success {
            border-left-color: var(--color-success);
        }

        .kpi-info {
            border-left-color: var(--color-info);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .student-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid transparent;
        }

        .risk-high {
            border-left-color: var(--color-danger);
            background: #fef2f2;
        }

        .risk-medium {
            border-left-color: var(--color-warning);
            background: #fffbeb;
        }

        .risk-low {
            border-left-color: var(--color-success);
            background: #f0fdf4;
        }

        .category-leve {
            background-color: #fef3c7;
            color: #92400e;
        }

        .category-grave {
            background-color: #fed7d7;
            color: #c53030;
        }

        .category-gravisima {
            background-color: #fed2d2;
            color: #9b2c2c;
        }

        .category-positiva {
            background-color: #c6f6d5;
            color: #2f855a;
        }

        .filter-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-filter {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-filter:hover {
            background: #4338ca;
        }

        .btn-clear {
            background: #6b7280;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-clear:hover {
            background: #4b5563;
        }

        @media (max-width: 768px) {
            .chart-container {
                padding: 1rem;
            }

            .kpi-card {
                margin-bottom: 1rem;
            }
        }

        .dataTables_wrapper {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        table.dataTable tbody tr.risk-high {
            background-color: #fef2f2 !important;
        }

        table.dataTable tbody tr.risk-medium {
            background-color: #fffbeb !important;
        }

        table.dataTable tbody tr.risk-low {
            background-color: #f0fdf4 !important;
        }

        .course-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .no-data-message {
            background: #f3f4f6;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            color: #6b7280;
            margin: 2rem 0;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-6"> <!-- Header -->
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-800 mb-2"> <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                Panel de Análisis de Anotaciones Estudiantiles </h1>
            <p class="text-gray-600 text-lg">Sistema de Alertas y Monitoreo Conductual</p>
        </header> <!-- File Upload -->
        <div class="filter-container">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0"> <label for="csvFile" class="block text-sm font-medium text-gray-700 mb-2"> <i
                            class="fas fa-upload mr-2"></i>Cargar Archivo de Anotaciones (CSV) </label> <input
                        type="file" id="csvFile" accept=".csv"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
                <div class="text-sm text-gray-500"> <i class="fas fa-info-circle mr-1"></i> Formato: CSV con columnas
                    requeridas </div>
            </div>
        </div> <!-- Loading Message -->
        <div id="loadingMessage" class="no-data-message" style="display: none;"> <i
                class="fas fa-spinner fa-spin text-3xl mb-4 text-blue-500"></i>
            <h3 class="text-xl font-semibold mb-2">Procesando datos...</h3>
            <p>Analizando anotaciones y generando estadísticas</p>
        </div> <!-- No Data Message -->
        <div id="noDataMessage" class="no-data-message"> <i class="fas fa-file-csv text-6xl mb-4 text-gray-400"></i>
            <h3 class="text-2xl font-semibold mb-2">¡Bienvenido al Panel de Análisis!</h3>
            <p class="text-lg mb-4">Para comenzar, carga tu archivo CSV con las anotaciones estudiantiles</p>
            <div class="text-sm text-gray-500">
                <p><strong>Columnas requeridas:</strong></p>
                <p>Fecha, Nombre del estudiante, curso, categoria, motivo, accion tomada, Asignatura, DOCENTE
                    /FUNCIONARIO</p>
            </div>
        </div> <!-- Dashboard Content -->
        <div id="dashboardContent" style="display: none;"> <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="kpi-card kpi-info p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Total Anotaciones</p>
                            <p id="totalAnnotations" class="text-3xl font-bold text-blue-600">0</p>
                        </div>
                        <div class="text-blue-500 text-3xl"> <i class="fas fa-clipboard-list"></i> </div>
                    </div>
                </div>
                <div class="kpi-card kpi-danger p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Estudiantes en Riesgo</p>
                            <p id="studentsAtRisk" class="text-3xl font-bold text-red-600">0</p>
                            <p class="text-xs text-gray-500">(>6 anotaciones negativas)</p>
                        </div>
                        <div class="text-red-500 text-3xl"> <i class="fas fa-exclamation-triangle"></i> </div>
                    </div>
                </div>
                <div class="kpi-card kpi-success p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Anotaciones Positivas</p>
                            <p id="positiveAnnotations" class="text-3xl font-bold text-green-600">0</p>
                        </div>
                        <div class="text-green-500 text-3xl"> <i class="fas fa-star"></i> </div>
                    </div>
                </div>
                <div class="kpi-card kpi-warning p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Curso Crítico</p>
                            <p id="criticalCourse" class="text-lg font-bold text-orange-600">-</p>
                        </div>
                        <div class="text-orange-500 text-3xl"> <i class="fas fa-school"></i> </div>
                    </div>
                </div>
            </div> <!-- Filters -->
            <div class="filter-container">
                <h3 class="text-lg font-semibold mb-4"> <i class="fas fa-filter mr-2"></i>Filtros de Análisis </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div> <label class="block text-sm font-medium text-gray-700 mb-1">Curso</label> <select
                            id="filterCourse"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los cursos</option>
                        </select>
                    </div>
                    <div> <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                        <select id="filterCategory"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las categorías</option>
                            <option value="Leve">Leve</option>
                            <option value="Grave">Grave</option>
                            <option value="Gravísima">Gravísima</option>
                            <option value="Positiva">Positiva</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Docente</label>
                        <select id="filterTeacher"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los docentes</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button id="applyFilters" class="btn-filter flex-1"> <i class="fas fa-search mr-1"></i>Filtrar
                        </button>
                        <button id="clearFilters" class="btn-clear"><i class="fas fa-times mr-1"></i>Limpiar
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <button id="filterRisk"
                        class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm hover:bg-red-200 transition-colors">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Solo Estudiantes en Riesgo
                    </button>
                    <button id="filterPositive"
                        class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm hover:bg-green-200 transition-colors">
                        <i class="fas fa-star mr-1"></i>Solo Anotaciones Positivas
                    </button>
                </div>
            </div> <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="chart-container">
                    <h3 class="text-lg font-semibold mb-4"> <i class="fas fa-chart-bar mr-2"></i>Anotaciones por Curso
                    </h3>
                    <div style="height: 400px;"> <canvas id="courseChart"></canvas> </div>
                </div>
                <div class="chart-container">
                    <h3 class="text-lg font-semibold mb-4"> <i class="fas fa-chart-pie mr-2"></i>Distribución por
                        Categoría </h3>
                    <div style="height: 400px;"> <canvas id="categoryChart"></canvas> </div>
                </div>
            </div> <!-- Student Rankings by Course -->
            <div id="studentRankings" class="mb-8"></div> <!-- Data Table -->
            <div class="chart-container">
                <h3 class="text-lg font-semibold mb-4"> <i class="fas fa-table mr-2"></i>Detalle de Anotaciones </h3>
                <div class="overflow-x-auto">
                    <table id="annotationsTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Estudiante</th>
                                <th>Curso</th>
                                <th>Categoría</th>
                                <th>Motivo</th>
                                <th>Acción Tomada</th>
                                <th>Asignatura</th>
                                <th>Docente</th>
                                <th>Riesgo</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        let globalData = [];
        let filteredData = [];
        let studentRiskCounts = {};
        let courseChart, categoryChart;
        let dataTable;

        document.getElementById('csvFile').addEventListener('change', handleFileSelect);
        document.getElementById('applyFilters').addEventListener('click', applyFilters);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);
        document.getElementById('filterRisk').addEventListener('click', filterByRisk);
        document.getElementById('filterPositive').addEventListener('click', filterByPositive);

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file && file.type === 'text/csv') {
                showLoading();
                const reader = new FileReader();
                reader.onload = function (e) {
                    parseCSV(e.target.result);
                };
                reader.readAsText(file);
            }
        } function showLoading() {
            document.getElementById('noDataMessage').style.display = 'none';
            document.getElementById('loadingMessage').style.display = 'block';
            document.getElementById('dashboardContent').style.display = 'none';
        }
        function hideLoading() {
            document.getElementById('loadingMessage').style.display = 'none';
            document.getElementById('dashboardContent').style.display = 'block';
        } function parseCSV(csvText) {
            try {
                const lines = csvText.split('\n').filter(line => line.trim());
                const headers = lines[0].split(',').map(h => h.trim().replace(/"/g, ''));
                globalData = [];
                for (let i = 1; i < lines.length; i++) {
                    const values = parseCSVLine(lines[i]);
                    if (values.length >= headers.length) {
                        const record = {};
                        headers.forEach((header, index) => {
                            record[header] = values[index] ? values[index].trim().replace(/"/g, '') : '';
                        });
                        globalData.push(record);
                    }
                }
                calculateRiskCounts();
                initializeDashboard();
                hideLoading();
            } catch (error) {
                console.error('Error parsing CSV:', error);
                alert('Error al procesar el archivo CSV. Verifique el formato.');
                document.getElementById('loadingMessage').style.display = 'none';
                document.getElementById('noDataMessage').style.display = 'block';
            }
        } function parseCSVLine(line) { const result = []; let current = ''; let inQuotes = false; for (let i = 0; i < line.length; i++) { const char = line[i]; const nextChar = line[i + 1]; if (char === '"') { if (inQuotes && nextChar === '"') { current += '"'; i++; } else { inQuotes = !inQuotes; } } else if (char === ',' && !inQuotes) { result.push(current); current = ''; } else { current += char; } } result.push(current); return result; } function calculateRiskCounts() { studentRiskCounts = {}; globalData.forEach(record => { const student = record['Nombre del estudiante']; const category = record['categoria']; if (!student) return; if (!studentRiskCounts[student]) { studentRiskCounts[student] = { negative: 0, positive: 0, course: record['curso'] || '' }; } if (category && category.toLowerCase().includes('positiva')) { studentRiskCounts[student].positive++; } else if (category && !category.toLowerCase().includes('positiva')) { studentRiskCounts[student].negative++; } }); } function getRiskLevel(student) { const counts = studentRiskCounts[student]; if (!counts) return 'low'; if (counts.negative > 6) return 'high'; if (counts.negative > 3) return 'medium'; return 'low'; } function getRiskLabel(student) { const counts = studentRiskCounts[student]; if (!counts) return 'Bajo'; if (counts.negative > 6) return 'Alto Riesgo'; if (counts.negative > 3) return 'Atención'; if (counts.positive > 0) return 'Positivo'; return 'Normal'; } function initializeDashboard() { filteredData = [...globalData]; populateFilters(); updateKPIs(); createCharts(); createStudentRankings(); initializeDataTable(); } function populateFilters() { const courses = [...new Set(globalData.map(record => record['curso']).filter(Boolean))].sort(); const teachers = [...new Set(globalData.map(record => record['DOCENTE /FUNCIONARIO']).filter(Boolean))].sort(); populateSelect('filterCourse', courses); populateSelect('filterTeacher', teachers); } function populateSelect(elementId, options) {
            const select = document.getElementById(elementId);
            const currentValue = select.value;
            select.innerHTML = select.children[0].outerHTML;


            // Keep first option 
            // options.forEach(
            //      option => {
            //          const optionElement = document.createElement('option');
            //          optionElement.value = option;
            //          optionElement.textContent = option;
            //          select.appendChild(optionElement); }
            //          );
            // select.value = currentValue;
            // }
            // function updateKPIs() {
            //      const total = filteredData.length;
            //      const studentsAtRisk = Object.values(studentRiskCounts).filter(counts => counts.negative > 6).length;
            //      const positiveCount = filteredData.filter(record => record['categoria'] && record['categoria'].toLowerCase().includes('positiva') ).length;
            // // Find course with most incidents
            //      const courseCounts = {};
            //      filteredData.forEach(
            //      record => { 
            //          const course = record['curso'];
            //      if (course) { courseCounts[course] = (courseCounts[course] || 0) + 1; } });
            //      const criticalCourse = Object.keys(courseCounts).reduce((a, b) => courseCounts[a] > courseCounts[b] ? a : b, '-' );
            //      document.getElementById('totalAnnotations').textContent = total;
            //      document.getElementById('studentsAtRisk').textContent = studentsAtRisk;
            //      document.getElementById('positiveAnnotations').textContent = positiveCount;
            //      document.getElementById('criticalCourse').textContent = criticalCourse;
            //  } function createCharts() {
            //      createCourseChart(); createCategoryChart();
            //      } function createCourseChart() {
            //      const ctx = document.getElementById('courseChart').getContext('2d'); if (courseChart) { courseChart.destroy(); } const courseCounts = {}; const coursePositiveCounts = {}; filteredData.forEach(record => { const course = record['curso']; const category = record['categoria']; if (course) { courseCounts[course] = (courseCounts[course] || 0) + 1; if (category && category.toLowerCase().includes('positiva')) { coursePositiveCounts[course] = (coursePositiveCounts[course] || 0) + 1; } } }); const courses = Object.keys(courseCounts).sort(); const negativeData = courses.map(course => courseCounts[course] - (coursePositiveCounts[course] || 0)); const positiveData = courses.map(course => coursePositiveCounts[course] || 0); courseChart = new Chart(ctx, { type: 'bar', data: { labels: courses, datasets: [{ label: 'Anotaciones Negativas', data: negativeData, backgroundColor: '#ef4444', borderColor: '#dc2626', borderWidth: 1 }, { label: 'Anotaciones Positivas', data: positiveData, backgroundColor: '#10b981', borderColor: '#059669', borderWidth: 1 }] }, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, stacked: true }, x: { stacked: true } }, plugins: { legend: { position: 'top' }, title: { display: false } } } }); } function createCategoryChart() { const ctx = document.getElementById('categoryChart').getContext('2d'); if (categoryChart) { categoryChart.destroy(); } const categoryCounts = {}; filteredData.forEach(record => { const category = record['categoria']; if (category) { categoryCounts[category] = (categoryCounts[category] || 0) + 1; } }); const categories = Object.keys(categoryCounts); const counts = Object.values(categoryCounts); const colors = { 'Leve': '#fbbf24', 'Grave': '#f87171', 'Gravísima': '#dc2626', 'Positiva': '#10b981' }; const backgroundColors = categories.map(cat => colors[cat] || '#6b7280'); categoryChart = new Chart(ctx, { type: 'doughnut', data: { labels: categories, datasets: [{ data: counts, backgroundColor: backgroundColors, borderColor: backgroundColors.map(color => color), borderWidth: 2 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' }, title: { display: false } } } }); } function createStudentRankings() { const container = document.getElementById('studentRankings'); container.innerHTML = ''; // Group students by course const courseStudents = {}; Object.keys(studentRiskCounts).forEach(student => { const course = studentRiskCounts[student].course; if (!courseStudents[course]) { courseStudents[course] = []; } courseStudents[course].push({ name: student, negative: studentRiskCounts[student].negative, positive: studentRiskCounts[student].positive, riskLevel: getRiskLevel(student) }); }); 
        
</script>


        </body >

</html >