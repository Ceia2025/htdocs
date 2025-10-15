
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
