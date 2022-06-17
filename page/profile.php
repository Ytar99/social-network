<?php
    @ULogin(1);
    Head('Профиль пользователя');
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


                    <div class="block">

                        <?php
                            if ($_SESSION['USER_AVATAR'] == 0)
                                $Avatar = 0;
                        else
                            $Avatar = $_SESSION['USER_AVATAR'].'/'.$_SESSION['USER_ID'];
                            echo '<img src="/resource/avatar/'.$Avatar.'.jpeg" alt="аватар" style="border: 1px solid #808080;" width="120" height="120" align=left>';
                        ?>



                        <div class="info">
                            <?php
                                echo 'ID: '.$_SESSION["USER_ID"];
                                echo '<br>Имя: '.$_SESSION["USER_NAME"];
                                echo '<br>Дата регистрации: '.$_SESSION["USER_REGDATE"];
                                echo '<br>E-Mail: '.$_SESSION["USER_EMAIL"];
                                echo '<br>Страна: '.$_SESSION["USER_COUNTRY"];
                            ?>
                         </div>
                        <a href="/account/logout" class="btn profileB">Выход</a>
                    </div>
                        <br>
                        <?php echo (
                        '
                        <form method="POST" class="profileF"  action="/account/edit" enctype="multipart/form-data">
                           Изменить данные аккаунта:
                            <input type="text" placeholder="Имя" name="name" maxlength="10" pattern="[А-Яа-яЁё-0-9]{2,50}" title="Не менее 2 и не более 50 русских букв или цифр." value="'.$_SESSION['USER_NAME'].'" >
                            <br>
                            <input type="email" placeholder="E-Mail" name="email" value="'.$_SESSION['USER_EMAIL'].'" required/>
                            <br>

                            <input class="Ppassword" type="password" placeholder="Старый пароль" name="opassword" maxlength="15" pattern="[A-Za-z-0-9]{2,15}" title="Не менее 6 и не более 15 латинских букв или цифр.">

                            <input class="Ppassword" type="password" placeholder="Новый пароль" name="npassword" maxlength="15" pattern="[A-Za-z-0-9]{2,15}" title="Не менее 6 и не более 15 латинских букв или цифр.">
                            <br>
                            Страна:

                            <select name="country">
                                '.str_replace('>'.$_SESSION['USER_COUNTRY'], 'selected>'.$_SESSION['USER_COUNTRY'],
                                          '
                                            <option value="0">Не указывать</option>
                                            <option value="1">Россия</option>
                                            <option value="2">США</option>
                                            <option value="3">Канада</option>
                                            <option value="4">Польша</option>
                                          ').'
                            </select>
                            <br>
                            <input type="file" id="avatar" name="avatar" class="inputfile" accept="image/jpeg,image/jpg" />
                            <label for="avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 20 17">
                                    <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path>
                                </svg>
                                <span>Выбери аватар</span>
                            </label>
                            <br>
                            <input type="submit" name="enter" value="Изменить данные" class="btn">
                            <input type="reset" value="Очистить" class="btn">
                        </form>
                        '
                        ); ?>




                </div>



            </div>
            <!-- .content -->
            <?php Footer(); ?>
                <!-- .footer -->
        </div>
        <!-- .wrapper -->

        <script>
        ;(function (document, window, index){
            'use strict';
            var inputs = document.querySelectorAll('.inputfile');
            Array.prototype.forEach.call(inputs, function (input) {
                var label = input.nextElementSibling,
                        labelVal = label.innerHTML;

                input.addEventListener('change', function (e) {
                    var fileName = '';
                    /*if (this.files && this.files.length > 1)
                        fileName = ( this.getAttribute('data-multiple-caption') || '' ).replace('{count}', this.files.length);
                    else*/
                        fileName = e.target.value.split('\\').pop();

                    if (fileName){
                        if (fileName.length>11)
                            fileName = fileName.substring(0,6)+"..."+fileName.substring(fileName.length-6,fileName.length);
                        label.querySelector('span').innerHTML = fileName;
                    }

                    else
                        label.innerHTML = labelVal;
                });

                // Firefox bug fix
                input.addEventListener('focus', function () {
                    input.classList.add('has-focus');
                });
                input.addEventListener('blur', function () {
                    input.classList.remove('has-focus');
                });
            });
        }(document, window, 0));
    </script>

    </body>

    </html>
