<section class="layout-column">
    <section class="left-column">
        <section class="box">
            <header>
                <div>
                    <h2>
                        <i class="m-icons">account_circle</i>
                        <span>Nueva Cuenta</span>
                    </h2>
                </div>
            </header>
            <div class="content">
                <div class="form-info">
                </div>
                <form id="register-form" action="/register">
                    <div class="input-group">
                        <div class="input-text">
                            <label class="m-icons" for="user-firstname">text_fields</label>
                            <input type="text" id="user-firstname" name="fname" placeholder="Nombres"
                                   autocomplete="given-name"
                                   required>
                            <label class="header" for="user-firstname">Nombres</label>
                        </div>
                        <div class="input-text">
                            <label class="m-icons" for="user-lastname">text_fields</label>
                            <input type="text" id="user-lastname" name="lname" placeholder="Apellidos"
                                   autocomplete="family-name"
                                   required>
                            <label class="header" for="user-lastname">Apellidos</label>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-text">
                            <label class="m-icons" for="user-name">person</label>
                            <input type="text" id="user-name" name="user" placeholder="Nombre de usuario"
                                   autocomplete="username"
                                   required>
                            <label class="header" for="user-name">Nombre de usuario</label>
                        </div>
                        <div class="input-text">
                            <label class="m-icons" for="user-email">mail</label>
                            <input type="email" id="user-email" name="email" placeholder="Correo electrónico (Opcional)"
                                   autocomplete="email">
                            <label class="header" for="user-email">Correo electrónico</label>
                        </div>
                    </div>

                    <div class="input-group">
                        <div class="input-text">
                            <label class="m-icons" for="user-pass">lock</label>
                            <input type="password" id="user-pass" name="pass" placeholder="Contraseña"
                                   autocomplete="new-password"
                                   minlength="8" maxlength="50" required>
                            <label class="header" for="user-pass">Contraseña</label>
                        </div>
                        <div class="input-text">
                            <label class="m-icons" for="user-pass-confirm">lock</label>
                            <input type="password" id="user-pass-confirm" name="repass"
                                   placeholder="Confirme la contraseña" autocomplete="off"
                                   minlength="8" maxlength="50" required>
                            <label class="header" for="user-pass-confirm">Contraseña (Confirmación)</label>
                        </div>
                    </div>
                    <button type="submit" class="btn-fill">
                        <i class="spinner-border" style="display: none"></i>
                        <span>Registrarse</span>
                    </button>
                </form>
            </div>
            <footer class="no-padding">
                <a href="/login" class="btn-text">
                    <span>¿Ya tienes una cuenta? ¡Inicia Sesión!</span>
                </a>
            </footer>
        </section>
    </section>

    <?php require \HS\libs\io\Path::GetViewClient('template/login_register_right_column') ?>
</section>