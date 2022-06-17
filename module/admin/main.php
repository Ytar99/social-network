<?php

    Head('Админ');
    $del_msg = '\'Вы точно хотите удалить?\'';
    if($_SESSION['SEARCH_LOGIN']){
        $INFO1 = '<div class="ChatBlock"> <span>Дата регистрации: '.$_SESSION['SEARCH_REGDATE'].' ['.UserGroup($_SESSION['SEARCH_GROUP']).'] <a href="/admin/query/user_delete/'.$_SESSION['SEARCH_LOGIN'].'" class="func" onclick="return confirm('.$del_msg.')">[Удалить]</a> <a href="/admin/user/user_view/'.$_SESSION['SEARCH_LOGIN'].'" class="func" >[Профиль]</a></span>'.$_SESSION['SEARCH_LOGIN'].' </div>';
    }
    else
    {
        $Query = mysqli_query($CONNECT, 'SELECT `login`, `regdate`, `groupp` FROM `users` ORDER BY `regdate` DESC LIMIT 50');
        while ($Row = mysqli_fetch_assoc($Query)) $INFO1 .= '<div class="ChatBlock"> <span>Дата регистрации: '.$Row['regdate'].' ['.UserGroup($Row['groupp']).'] <a href="/admin/query/user_delete/'.$Row['login'].'" class="func" onclick="return confirm('.$del_msg.')">[Удалить]</a> <a href="/admin/user/user_view/'.$Row['login'].'" class="func" >[Профиль]</a></span>'.$Row['login'].' </div>';
    }

    $Query = mysqli_query($CONNECT, 'SELECT `id`, `user`, `message`, `time` FROM `chat` ORDER BY `time` DESC LIMIT 50');
    while ($Row = mysqli_fetch_assoc($Query)) $INFO2 .= '<div class="ChatBlock"> <span>'.$Row['time'].' '.$Row['user'].' <a href="/admin/query/msg_delete/'.$Row['id'].'" class="func" onclick="return confirm('.$del_msg.')">[Удалить]</a></span>'.$Row['message'].' </div>';

    $_SESSION['SEARCH_REGDATE'] = '';
    $_SESSION['SEARCH_LOGIN'] = '';
    $_SESSION['SEARCH_GROUP'] = '';
?>

    <body>
        <div class="wrapper">
            <header class="header">
                <a href="/index">
                    <p>Социальная сеть</p>
                </a>
            </header>
            <!-- .header-->
            <div class="content">
                <?php
                @Menu();
                @MessageShow();
            ?>
                    <br>
                    <div class="pageAblock1">
                       <p>Пользователи (последние 50 регистраций):</p>

                            <div class="sform">
                            <form method="POST" action="/admin/query/search">
                                <input class="" type="search" name="search" placeholder="Найти по логину...">
                                <input type="submit" name="enter" value="Найти">
                            </form>
                    </div>

                        <div class="Ablock1">

                            <?PHP
                echo $INFO1;
            ?>
                        </div>
                    </div>
                    <div class="pageAblock2">
                       <p style="height: 46px;">Чат (последние 50 сообщений):</p>
                        <div class="Ablock2">

                            <?PHP
                echo $INFO2;
            ?>
                        </div>
                    </div>

                    <div class="aform">
                        <form method="POST" action="/account/adminlogout">
                            <input class="btn" type="submit" name="exit" value="Выйти"> </form>
                    </div>
            </div>
            <!-- .content -->
            <?php Footer(); ?>
                <!-- .footer -->
        </div>
        <!-- .wrapper -->
    </body>

    </html>
