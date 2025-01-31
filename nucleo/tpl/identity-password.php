<body id="embedpage">
<script src='https://www.google.com/recaptcha/api.js'></script>

<div id="overlay"></div>

<div id="container">

    <div class="settings-container clearfix">

        <h1>E-mail Login manage</h1>

        <div id="back-link">

            <a href="%www%/identity/avatars">Meus avatares</a> &raquo; <a href="%www%/identity/settings"> Gerenciar
                emails</a> &raquo; Trocar senha

        </div>

        <div style="padding: 0 10px">
            %errors%

            <form action="%www%/identity/password_change" id="change-password" method="post">

                <input type="hidden" name="fromClient" value="false"/>

                <div class="field field-currentpassword">

                    <label for="current-password">Antiga senha</label>

                    <input type="password" id="current-password" size="35" name="currentPassword" value=""
                           class="password-field" maxlength="32"/>

                    <p class="help"></p>

                </div>


                <div class="form-box">

                    <div class="field field-password">

                        <label for="password">Nova senha</label>

                        <input type="password" id="password" size="35" name="newPassword" value=""
                               class="password-field" maxlength="32"/>

                    </div>


                    <div class="field field-password2">

                        <label for="password2">Nova senha (Novamente)</label>

                        <input type="password" id="password2" size="35" name="retypedNewPassword" value=""
                               class="password-field" maxlength="32"/>

                        <p class="help">Digite uma nova senha, que tenha de 6 a 10 dígitos, com pelo menos uma letra
                            maiúscula e um numero (pode conter #$%^&).</p>

                    </div>

                </div>


                <div id="register-fieldset-captcha" class="field field-captcha">

                 <!--   <div id="captcha-container">

                        <label>Digite a frase que você vê abaixo.</label>

                        %recaptcha_html%

                        <div id="recaptcha_image" class="register-label"></div>

                    </div>-->



                    <div class="field field-recaptcha">

                        <div id="captcha-container" class="input-field">
                            <div class="g-recaptcha"
                                 data-sitekey="6Ld8ShAUAAAAAC_2-ccVvXhefP6AQs315Z3jb0zv"></div>
                        </div>
                    </div>



                </div>

                <div class="js" style="overflow: hidden">

                    <a href="#" class="new-button password-button" id="next-btn"
                       onclick="$(this).up('form').submit(); return false;"><b>Enviar</b><i></i></a>

                    <input type="submit" id="next" value="Verander" style="display: none;"/>

                </div>


            </form>

        </div>

    </div>

    <div class="settings-container-bottom"></div>
