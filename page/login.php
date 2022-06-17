<?php
    @ULogin(0);
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
                    @Menu();
                    @MessageShow();
                ?>
                   <div class="page">
                    <br>
                    <div class="form">

                        <form method="POST" action="account/login">
                            <p>Введите данные для входа:</p>
                            <input type="text" placeholder="Логин*" name="login" maxlength="10" pattern="[A-Za-z-0-9]{3,10}" title="Не менее 3 и не более 10 латинских букв или цифр." required>
                            <br>
                            <input type="password" placeholder="Пароль*" name="password" maxlength="15" pattern="[A-Za-z-0-9]{2,15}" title="Не менее 6 и не более 15 латинских букв или цифр." required>
                            <br>
                            <input type="checkbox" name="remember"> Запомнить меня <br>
                            <input type="submit" name="enter" value="Войти" class="btn">

                            <input type="reset" value="Очистить" class="btn"> </form>
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
