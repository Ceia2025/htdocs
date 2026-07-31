<!DOCTYPE html>
<html lang="es" class="login-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña — SAAT</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="/app/public/css/style.css">
</head>

<body class="login-page">
    <div class="bg-scene"></div>
    <div class="grid-lines"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="wrapper">
        <div class="card card-split">

            <!-- Columna izquierda: marca -->
            <div class="card-brand">
                <img src="/app/public/img/SAATlog.png" alt="Logo CEIA" class="logo-img">
                <div class="logo-title">Recuperar acceso</div>
                <span class="logo-badge">CEIA Juanita Zúñiga · Parral</span>
            </div>

            <div class="card-divider-v"></div>

            <!-- Columna derecha: formulario -->
            <div class="card-form">

                <p class="text-muted"
                    style="margin-bottom:1.25rem;font-size:.9rem;line-height:1.4;color:rgba(255,255,255,0.6);">
                    Paso 1 de 2 — Ingresa tu usuario o correo y tu RUN para verificar tu identidad.
                </p>

                <form action="index.php?action=recuperar_check" method="POST">

                    <div class="form-group">
                        <label for="login">Usuario o Email</label>
                        <div class="input-wrap">
                            <input id="login" type="text" name="login" required placeholder="correo@ceia.cl"
                                autocomplete="username">
                            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="run">RUN</label>
                        <div class="input-wrap">
                            <input id="run" type="text" name="run" required placeholder="12.345.678-9">
                            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V7z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 15h4M15 10h1" />
                            </svg>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Continuar</button>

                    <?php if (!empty($error)): ?>
                        <div class="error-box">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.72-3L13.72 4c-.77-1.33-2.67-1.33-3.44 0L3.35 16c-.78 1.33.18 3 1.72 3z" />
                            </svg>
                            <span><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                </form>

                <div class="card-footer" style="margin-top:1rem;">
                    <a href="index.php?action=login" class="forgot">← Volver al inicio de sesión</a>
                </div>
                <div class="card-footer">
                    © <?= date('Y') ?> CEIA Juanita Zúñiga &nbsp;·&nbsp; <span>SAAT v1.0</span>
                </div>

            </div>

        </div>
    </div>
</body>

</html>