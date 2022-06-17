<?php
    @ALogin(0);
    @Head('Авторизация');
?>

    <body>
        <div class="wrapper">
            <header class="header">
                <a href="/index"><p>Социальная сеть</p></a>
            </header>
            <!-- .header-->
            <div class="content">
                <?php
                    Menu();
                    MessageShow();
                ?>
                   <div class="page">
                    <br>
                    <div class="form">

                        <form method="POST" action="/account/adminlogin">
                            <p>Введите пароль для входа:</p>
                            <input type="password" placeholder="Пароль*" name="apassword" maxlength="15" pattern="[A-Za-z-0-9]{2,15}" title="Не менее 6 и не более 15 латинских букв или цифр." required>
                            <br>
                            <input type="submit" name="enter" value="Войти" class="btn">
                            <input type="reset" value="Очистить" class="btn">
                        </form>
                    </div>
                </div>
            </div>
            <!-- .content -->
            <?php Footer(); ?>
                <!-- .footer -->
        </div>
        <!-- .wrapper -->
    </body>

    </html>
