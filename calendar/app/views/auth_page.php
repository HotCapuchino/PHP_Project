<div class="general-wrapper">
    <div class="content-wrapper">
        <form action="/login" class="auth-form" method="POST">
            <h1 class="auth-form__heading">Login Calendar</h1>
            <div <?= 'class="' . $error_block_class . '"' ?>><?= $error_message ?></div>
            <label class="login-block">
                <?php
                $login_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['login']) {
                        echo '<div class="error-warning">' . $error_fields['login'] . '</div>';
                        $login_class = ' wrong_input';
                    }
                }
                ?>
                <input class="login-block__login<?= $login_class ?>" type="text" name="login" placeholder="Login" value="<?= sizeof($user_values) ? $user_values['login'] : '' ?>">
            </label>
            <label class="password-block">
                <?php
                $password_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['password']) {
                        echo '<div class="error-warning">' . $error_fields['password'] . '</div>';
                        $password_class = ' wrong_input';
                    }
                }
                ?>
                <input class="password-block__password<?= $password_class ?>" type="password" name="password" placeholder="Password" value="">
            </label>
            <button class="auth-form__login-btn" type="submit">Log In</button>
            <?php
                $error_block_class = 'error-block none';
                if ($error_message) $error_block_class = 'error-block';
            ?>
        </form>
        <div class="register-block">
            <div class="register-block__question">Don't have an account?</div>
            <a href="/register" class="register-block__register-link">Sign Up</a>
        </div>
    </div>
</div>