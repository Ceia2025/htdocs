<h2>Crear Alumno</h2>
<form action="index.php?action=alumno_store" method="POST">
    <label>RUN:</label>
    <input type="text" name="run" required><br>

    <label>Nombre:</label>
    <input type="text" name="nombre" required><br>

    <label>Apellido Paterno:</label>
    <input type="text" name="apepat" required><br>

    <label>Apellido Materno:</label>
    <input type="text" name="apemat"><br>

    <label>Fecha de Nacimiento:</label>
    <input type="date" name="fechanac"><br>

    <label>¿Mayor de Edad?</label>
    <select name="mayoredad">
        <option value="Si">Si</option>
        <option value="No">No</option>
    </select><br>

    <label>Número de Hijos:</label>
    <input type="number" name="numerohijos" min="0"><br>

    <label>Teléfono:</label>
    <input type="text" name="telefono"><br>

    <label>Email:</label>
    <input type="email" name="email"><br>

    <label>Sexo:</label>
    <select name="sexo" required>
        <option value="Femenino">Femenino</option>
        <option value="Masculino">Masculino</option>
        <option value="Prefiere no identificar">Prefiere no identificar</option>
        <option value="Otro">Otro</option>
    </select><br>

    <label>Nacionalidad:</label>
    <input type="text" name="nacionalidades"><br>

    <label>Región:</label>
    <input type="text" name="region"><br>

    <label>Ciudad:</label>
    <input type="text" name="ciudad"><br>

    <label>Etnia:</label>
    <select name="cod_etnia">
        <option value="Ninguna">Ninguna</option>
        <option value="Mapuche">Mapuche</option>
        <option value="Aymara">Aymara</option>
        <option value="Rapa Nui">Rapa Nui</option>
        <option value="Lickan Antai (Atacameños)">Lickan Antai (Atacameños)</option>
        <option value="Quechua">Quechua</option>
        <option value="Colla">Colla</option>
        <option value="Diaguita">Diaguita</option>
        <option value="Chango">Chango</option>
        <option value="Kawésqar">Kawésqar</option>
        <option value="Yagán">Yagán</option>
        <option value="Selk nam">Selk nam</option>
    </select><br><br>

    <button type="submit">Guardar</button>
</form>
