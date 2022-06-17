<?php
    include_once 'settings.php';
    session_start();
    $CONNECT = mysqli_connect(HOST, USER, PASS, DB);
    error_reporting(0);
    if ($_SESSION['USER_LOGIN_IN'] != 1 and $_COOKIE['user']){
        $Row = mysqli_fetch_assoc(mysqli_query($CONNECT,
                                                    "SELECT `id`, `name`, `regdate`, `email`, `login`, `country`, `avatar`
                                                    FROM `users` WHERE `password` = '$_COOKIE[user]'"));
        $_SESSION['USER_ID'] = $Row['id'];
        $_SESSION['USER_LOGIN'] = $Row['login'];
        $_SESSION['USER_NAME'] = $Row['name'];
        $_SESSION['USER_REGDATE'] = $Row['regdate'];
        $_SESSION['USER_EMAIL'] = $Row['email'];
        $_SESSION['USER_COUNTRY'] = UserCountry($Row['country']);
        $_SESSION['USER_AVATAR'] = $Row['avatar'];
        $_SESSION['USER_LOGIN_IN'] = 1;
    }

    // Обработка запроса. Если он пуст, переход на index.php
    if ($_SERVER['REQUEST_URI'] == '/')
    {
        $Page = 'index';
        $Module = 'index';
    }
    else // Если не пуст, разбиваем запрос на составляющие через слеш
    {
        $URL_Path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $URL_Parts = explode('/', trim($URL_Path,' /'));
        $Page = array_shift($URL_Parts);
        $Module = array_shift($URL_Parts);

        if (!empty($Module))
        {
            $Param = array();
            for ($i = 0; $i < count($URL_Parts); $i++)
            {
                $Param[$URL_Parts[$i]] = $URL_Parts[++$i];
            }
        }
    }

    function MessageSend($p1, $p2, $p3 = ''){
        if ($p1 == 1) $p1 = 'Ошибка';
        else if ($p1 == 2) $p1 = 'Подсказка';
        else if ($p1 == 3) $p1 = 'Информация';
        $_SESSION['message'] = '<div class="MessageBlock"><b>'.$p1.'</b>: '.$p2.'</div>';
        if ($p3) $_SERVER['HTTP_REFERER'] = $p3;
        exit (header('Location: '.$_SERVER['HTTP_REFERER']));
    }

    function MessageShow(){
        if ($_SESSION['message']) $Message = $_SESSION['message'];
        echo $Message;
        $_SESSION['message'] = array();
    }


    if ($Page == 'index') include('page/index.php');
    else if ($Page == 'register') include('page/register.php');
    else if ($Page == 'login') include('page/login.php');
    else if ($Page == 'account') include('form/account.php');
    else if ($Page == 'profile') include('page/profile.php');
    else if ($Page == 'chat') include('page/chat.php');

    else if ($Page == 'admin') {
        if ($_SESSION['ADMIN_LOGIN_IN']){
            if (!$Module)
                include('module/admin/main.php');
            else if ($Module == 'query')
                include('module/admin/query.php');
            else if ($Module == 'user')
                include('module/admin/user.php');
        }
        else if ($Module == 'adminlogin'){
                include('module/admin/adminlogin.php');
                /*MessageSend(2, 'Введите пароль администратора.', '/admin/adminlogin');*/
        }
/*
        else if ($Module == 'admin'){
                $_SESSION['ADMIN_LOGIN_IN'] = 1;
                MessageSend(3, 'Успешный вход.', '/');
        }
        else
           MessageSend(1, 'Неверный пароль.', '/admin/adminlogin');*/
    }






function ULogin($p1){
    if ($p1 <= 0 and $_SESSION['USER_LOGIN_IN'] != $p1)
        MessageSend(1, 'Данная страница доступна только для <b>гостей</b>.', '/');
    else if ($_SESSION['USER_LOGIN_IN'] != $p1)
        MessageSend(1, 'Данная страница доступна только для <b>авторизованных пользователей</b>.', '/');
}

function ALogin($p1){
if ($p1 <= 0 and $_SESSION['ADMIN_LOGIN_IN'] != $p1)
    MessageSend(1, 'Данная страница доступна только для <b>гостей</b>.', '/');
else if ($_SESSION['ADMIN_LOGIN_IN'] != $p1)
    MessageSend(1, 'Данная страница доступна только для <b>администраторов</b>.', '/');

    /*
    else if ($p1 = 2 and $_SESSION['USER_LOGIN_IN'] != $p1)
        MessageSend(1, 'Данная страница доступна только для администраторов.', '/');
    */
}


function Menu(){
    if ($_SESSION['USER_LOGIN_IN'] != 1)
        $Menu = '<a href="/register"><div class="menu">Регистрация</div></a><a href="/login"><div class="menu">Вход</div></a>';
    else
        $Menu = '<a href="/profile"><div class="menu">Профиль: '.$_SESSION['USER_NAME'].'</div></a> <a href="/chat"><div class="menu">Чат</div></a>';
    /*
    echo '<div class="menu_strip"><a href="index"><div class="menu">Главная</div></a><a href="register"><div class="menu">Регистрация</div></a><a href="login">                    <div class="menu">Вход</div></a></div>';
    */
    if ($_SESSION['ADMIN_LOGIN_IN'] != 1)
        echo '<div class="menu_strip"> <a href="/admin/adminlogin"><div class="menu">Админ-панель</div></a>'.$Menu.'</div>';
    else
        echo '<div class="menu_strip"> <a href="/admin"><div class="menu">Админ-панель</div></a>'.$Menu.'</div>';
}


function FormChars ($p1){
    return nl2br(htmlspecialchars(trim($p1), ENT_QUOTES), false);
}

function GenPass ($p1, $p2) {
    return md5('social'.md5('321'.$p1.'123').md5('678'.$p2.'890'));
}

function Head($p1){
    echo '<!DOCTYPE html><html><head><meta charset="utf-8" /><title>'.$p1.'</title><meta name="keywords" content="" /><meta name="description" content="" /><link rel="stylesheet" href="/resource/style.css"></head>';
}


function UserCountry($p1){
    if($p1 == 0)
        return 'Не указано';
    else if ($p1 == 1)
        return 'Россия';
    else if ($p1 == 2)
        return 'США';
    else if ($p1 == 3)
        return 'Канада';
    else if ($p1 == 4)
        return 'Польша';

}

function UserGroup($p1){
    if($p1 == 0)
        return 'Пользователь';
    else if ($p1 == 1)
        return 'Администратор';
    else if ($p1 == -1)
            return 'Заблокирован';
}


function UAccess($p1){
    if ($_SESSION['USER_GROUP'] < $p1)
        MessageSend(1, "У Вас нет прав доступа для просмотра данной страницы.", '/');
}



function Footer(){
    echo '<footer class="footer"> (c) SocialNet - 2020</footer>';
}


?>
