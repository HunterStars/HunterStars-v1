<section class="layout-column">
    <section class="left-column">
        <section class="box">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">verified_user</i>
                        <span>Autenticación</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <div class="form-info">
                </div>
                <form id="login-form" action="/login">
                    <div class="input-text">
                        <label class="m-icons" for="user-name">person</label>
                        <input type="text" id="user-name" name="user-name" placeholder="Nombre de usuario" autocomplete="username"
                               value="<?= htmlspecialchars(\HS\libs\view\View::GetClientData()->CurrentNick) ?>"
                               required>
                        <label class="header" for="user-name">Nombre de usuario</label>
                    </div>
                    <div class="input-text">
                        <label class="m-icons" for="user-pass">key</label>
                        <input type="password" id="user-pass" name="user-pass" placeholder="Contraseña" autocomplete="current-password"
                               minlength="8" maxlength="50" required>
                        <label class="header" for="user-pass">Contraseña</label>
                    </div>
                    <button type="submit" class="btn-fill">
                        <i class="spinner-border" style="display: none"></i>
                        <span>Iniciar Sesión</span>
                    </button>
                </form>
            </div>
            <footer class="no-padding">
                <a href="/register" class="btn-text">
                    <span>¿No tienes una cuenta? ¡Regístrate!</span>
                </a>
            </footer>
        </section>
    </section>

    <?php require \HS\libs\io\Path::GetViewClient('template/login_register_right_column') ?>
</section>