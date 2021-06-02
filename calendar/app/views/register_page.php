<div class="general-wrapper">
    <div class="content-wrapper">
        <form action="/register/sign-up" class="register-form" method="POST">
            <h1 class="register-form__heading">Register</h1>
            <div <?= 'class="' . $error_block_class . '"' ?>><?= $error_message ?></div>
            <label class="name-block">
                <?php
                $name_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['name']) {
                        echo '<div class="error-warning">' . $error_fields['name'] . '</div>';
                        $name_class = 'wrong_input';
                    }
                }
                ?>
                <div class="name-block__name-title">Enter your name</div>
                <input class="name-block__name<?= $name_class ?>" type="text" name="name" placeholder="Name" value="<?= sizeof($user_values) ? $user_values['name'] : '' ?>">
            </label>
            <label class="login-block">
                <?php
                $login_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['login']) {
                        echo '<div class="error-warning">' . $error_fields['login'] . '</div>';
                        $login_class = 'wrong_input';
                    }
                }
                ?>
                <div class="login-block__login-title">Enter your login</div>
                <input class="login-block__login<?= $login_class ?>" type="text" name="login" placeholder="Login" value="<?= sizeof($user_values) ? $user_values['login'] : '' ?>">
            </label>
            <label class="password-block">
                <?php
                $password_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['password']) {
                        echo '<div class="error-warning">' . $error_fields['password'] . '</div>';
                        $password_class = 'wrong_input';
                    }
                }
                ?>
                <div class="password-block__password-title">Make up a password</div>
                <input class="password-block__password<?= $password_class ?>" type="password" name="password" placeholder="Password">
            </label>
            <label class="password-block">
                <?php
                $repeated_password_class = '';
                if (sizeof($error_fields)) {
                    if ($error_fields['repeated_password']) {
                        echo '<div class="error-warning">' . $error_fields['repeated_password'] . '</div>';
                        $repeated_password_class = 'wrong_input';
                    }
                }
                ?>
                <div class="password-block__password-title">Repeat your password</div>
                <input class="password-block__password<?= $repeated_password_class ?>" type="password" name="repeated_password" placeholder="Repeated password">
            </label>
            <button class="register-form__register-btn">Sign Up</button>
            <?php
            $error_block_class = 'error-block none';
            if ($error_message) $error_block_class = 'error-block';
            ?>
        </form>
        <div class="auth-block">
            <a href="/" class="auth-block__auth-link">Get back to main page</a>
        </div>
    </div>
</div>