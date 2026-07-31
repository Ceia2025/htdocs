<!DOCTYPE html>
<html lang="es" class="login-page">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva contraseña — SAAT</title>
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
                <div class="logo-title">Nueva contraseña</div>
                <span class="logo-badge">CEIA Juanita Zúñiga · Parral</span>
            </div>

            <div class="card-divider-v"></div>

            <!-- Columna derecha: formulario -->
            <div class="card-form">

                <p class="text-muted" style="margin-bottom:1.25rem;font-size:.9rem;line-height:1.4;color:rgba(255,255,255,0.6);">
                    Paso 2 de 2 — Define tu nueva contraseña. Mínimo 6 caracteres.
                </p>

                <form action="index.php?action=recuperar_guardar" method="POST">

                    <div class="form-group">
                        <label for="password">Nueva contraseña</label>
                        <div class="input-wrap">
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                autocomplete="new-password" minlength="6">
                            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password2">Repetir contraseña</label>
                        <div class="input-wrap">
                            <input id="password2" type="password" name="password2" required placeholder="••••••••"
                                autocomplete="new-password" minlength="6">
                            <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Guardar contraseña</button>

                    <?php if (!empty($error)): ?>
                        <div class="error-box">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.72-3L13.72 4c-.77-1.33-2.67-1.33-3.44 0L3.35 16c-.78 1.33.18 3 1.72 3z" />
                            </svg>
                            <span><?= htmlspecialchars($error) ?></span>
                        </div>
                    <?php endif; ?>

                </form>

                <div class="card-footer" style="margin-top:1rem;">
                    <a href="index.php?action=login" class="forgot">← Cancelar y volver al login</a>
                </div>

            </div>

        </div>
    </div>
</body>

</html>