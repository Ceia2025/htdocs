<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SAAT</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --azul-oscuro: #0a1628;
            --azul-medio: #0d2247;
            --azul-vivo: #1a4fd6;
            --azul-claro: #3b82f6;
            --amarillo: #f5c518;
            --amarillo-claro: #fde68a;
            --blanco: #ffffff;
            --gris-suave: rgba(255, 255, 255, 0.07);
            --borde: rgba(255, 255, 255, 0.12);
        }

        html,
        body {
            height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--azul-oscuro);
            overflow: hidden;
        }

        /* ── Fondo animado ── */
        .bg-scene {
            position: fixed;
            inset: 0;
            z-index: 0;
            background:
                radial-gradient(ellipse 80% 60% at 10% 20%, rgba(26, 79, 214, 0.45) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 90% 80%, rgba(245, 197, 24, 0.18) 0%, transparent 55%),
                radial-gradient(ellipse 50% 70% at 50% 50%, rgba(13, 34, 71, 0.9) 0%, transparent 80%),
                var(--azul-oscuro);
        }

        /* Orbes flotantes */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.35;
            animation: drift 12s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            width: 500px;
            height: 500px;
            background: var(--azul-vivo);
            top: -120px;
            left: -80px;
            animation-duration: 14s;
        }

        .orb-2 {
            width: 350px;
            height: 350px;
            background: var(--amarillo);
            bottom: -80px;
            right: -60px;
            animation-duration: 10s;
            animation-delay: -4s;
            opacity: 0.2;
        }

        .orb-3 {
            width: 250px;
            height: 250px;
            background: var(--azul-claro);
            top: 40%;
            right: 15%;
            animation-duration: 16s;
            animation-delay: -7s;
            opacity: 0.2;
        }

        @keyframes drift {
            from {
                transform: translate(0, 0) scale(1);
            }

            to {
                transform: translate(30px, 20px) scale(1.08);
            }
        }

        /* Grid de líneas decorativas */
        .grid-lines {
            position: fixed;
            inset: 0;
            z-index: 0;
            opacity: 0.04;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.8) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.8) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        /* ── Contenedor principal ── */
        .wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1.5rem;
        }

        /* ── Card ── */
        .card {
            width: 100%;
            max-width: 420px;
            background: rgba(13, 34, 71, 0.55);
            backdrop-filter: blur(24px) saturate(1.4);
            -webkit-backdrop-filter: blur(24px) saturate(1.4);
            border: 1px solid var(--borde);
            border-radius: 24px;
            padding: 2.8rem 2.4rem 2.4rem;
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.04) inset,
                0 32px 80px rgba(0, 0, 0, 0.5),
                0 0 60px rgba(26, 79, 214, 0.15);
            animation: cardIn 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(28px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Logo ── */
        .logo-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
            animation: fadeDown 0.6s 0.15s both;
        }

        @keyframes fadeDown {
            from {
                opacity: 0;
                transform: translateY(-12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-img {
            width: 200px;
            height: 200px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(245, 197, 24, 0.35));
            transition: transform 0.4s ease;
        }

        .logo-img:hover {
            transform: scale(1.06) rotate(-2deg);
        }

        .logo-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--blanco);
            letter-spacing: -0.02em;
            text-align: center;
            line-height: 1.2;
        }

        .logo-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--amarillo), #f97316);
            color: var(--azul-oscuro);
            font-family: 'Sora', sans-serif;
            font-size: 0.80rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            padding: 0.2rem 0.7rem;
            border-radius: 99px;
            margin-top: 0.1rem;
        }

        /* ── Divisor ── */
        .divider {
            height: 1px;
            margin-bottom: 1.8rem;
            background: linear-gradient(90deg, transparent, var(--borde), transparent);
        }

        /* ── Formulario ── */
        .form-group {
            margin-bottom: 1.1rem;
            animation: fadeUp 0.5s both;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.25s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.35s;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        label {
            display: block;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.55);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.3);
            pointer-events: none;
            transition: color 0.2s;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.7rem;
            background: var(--gris-suave);
            border: 1px solid var(--borde);
            border-radius: 12px;
            color: var(--blanco);
            font-family: 'DM Sans', sans-serif;
            font-size: 1.1rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }

        input::placeholder {
            color: rgba(255, 255, 255, 0.2);
        }

        input:focus {
            border-color: var(--azul-claro);
            background: rgba(59, 130, 246, 0.08);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        input:focus+.input-icon,
        .input-wrap:focus-within .input-icon {
            color: var(--azul-claro);
        }

        /* Fila olvidé contraseña */
        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .forgot {
            font-size: 0.9rem;
            color: var(--amarillo);
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .forgot:hover {
            opacity: 1;
        }

        /* ── Botón ── */
        .btn-submit {
            width: 100%;
            margin-top: 1.6rem;
            padding: 0.85rem;
            background: linear-gradient(135deg, var(--azul-vivo) 0%, #2563eb 50%, #1d4ed8 100%);
            border: none;
            border-radius: 12px;
            color: var(--blanco);
            font-family: 'Sora', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(26, 79, 214, 0.45);
            animation: fadeUp 0.5s 0.45s both;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(245, 197, 24, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.25s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(26, 79, 214, 0.55);
        }

        .btn-submit:hover::before {
            opacity: 1;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Shimmer en botón */
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            animation: shimmer 3s 1.5s infinite;
        }

        @keyframes shimmer {
            from {
                left: -60%;
            }

            to {
                left: 130%;
            }
        }

        /* ── Error ── */
        .error-box {
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 0.7rem 1rem;
            color: #fca5a5;
            font-size: 1.1rem;
            animation: shake 0.35s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            75% {
                transform: translateX(6px);
            }
        }

        /* ── Footer ── */
        .card-footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.2);
            letter-spacing: 0.04em;
            animation: fadeUp 0.5s 0.55s both;
        }

        .card-footer span {
            color: var(--amarillo);
            opacity: 0.7;
        }
    </style>
</head>

<body>
    <div class="bg-scene"></div>
    <div class="grid-lines"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="wrapper">
        <div class="card">

            <!-- Logo -->
            <div class="logo-wrap">
                <img src="/app/public/img/SAATlog.png" alt="Logo CEIA" class="logo-img">
                <div>
                    <div class="logo-title">Sistema SAAT</div>
                    <div style="text-align:center; margin-top:0.4rem;">
                        <span class="logo-badge">CEIA Juanita Zúñiga · Parral</span>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Formulario -->
            <form action="index.php?action=doLogin" method="POST">

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
                    <div class="label-row">
                        <label for="password" style="margin-bottom:0">Contraseña</label>
                        <a href="#" class="forgot">¿Olvidó su contraseña?</a>
                    </div>
                    <div class="input-wrap">
                        <input id="password" type="password" name="password" required placeholder="••••••••"
                            autocomplete="current-password">
                        <svg class="input-icon" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Ingresar al Sistema</button>

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

            <div class="card-footer">
                © <?= date('Y') ?> CEIA Juanita Zúñiga &nbsp;·&nbsp; <span>SAAT v1.0</span>
            </div>
            <div class="card-footer">
                Desarrollado por &nbsp;·&nbsp; <span>Daniel Scarlazzetta</span>
            </div>

        </div>
    </div>
</body>

</html>