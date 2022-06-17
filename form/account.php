<?php

if ($Module == 'logout' and $_SESSION['USER_LOGIN_IN'] == 1){
    if ($_COOKIE['user']){
        setcookie('user', '', strtotime('-30 days'), '/');
        unset($_COOKIE['user']);
    }
    session_unset();
    exit(header('Location: /login'));
}


if ($Module == 'edit' and $_POST['enter']){
    ULogin(1);
    $_POST['opassword'] = FormChars($_POST['opassword']);
    $_POST['npassword'] = FormChars($_POST['npassword']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['country'] = FormChars($_POST['country']);

    if ($_POST['opassword'] or $_POST['npassword']){
        if (!$_POST['opassword'])
            MessageSend(1, 'Не указан старый пароль.');
        if (!$_POST['npassword'])
            MessageSend(1, 'Не указан новый пароль.');


        if ($_SESSION['USER_PASSWORD'] != GenPass($_POST['opassword'], $_SESSION['USER_LOGIN']))
            MessageSend(1, 'Неверный старый пароль.');

        $Password = GenPass($_POST['npassword'], $_SESSION['USER_LOGIN']);

        mysqli_query($CONNECT, "UPDATE `users` SET `password` = '$Password' WHERE `id` = $_SESSION[USER_ID]");
        $_SESSION['USER_PASSWORD'] = $Password;

    }

    if ($_POST['name'] != $_SESSION['USER_NAME']){
        mysqli_query($CONNECT, "UPDATE `users` SET `name` = '$_POST[name]' WHERE `id` = $_SESSION[USER_ID]");
        $_SESSION['USER_NAME'] = $_POST['name'];
    }

    if ($_POST['email'] != $_SESSION['USER_EMAIL']){
        mysqli_query($CONNECT, "UPDATE `users` SET `email` = '$_POST[email]' WHERE `id` = $_SESSION[USER_ID]");
        $_SESSION['USER_EMAIL'] = $_POST['email'];
    }

    if (UserCountry($_POST['country']) != $_SESSION['USER_COUNTRY']){
        mysqli_query($CONNECT, "UPDATE `users` SET `country` = $_POST[country] WHERE `id` = $_SESSION[USER_ID]");
        $_SESSION['USER_COUNTRY'] = UserCountry($_POST['country']);
    }


    if ($_FILES['avatar']['tmp_name']){
        if ($_FILES['avatar']['type'] != 'image/jpeg')
            MessageSend(1, 'Неверный тип изображения.');
    if ($_FILES['avatar']['size'] > 5000000)
            MessageSend(1, 'Размер изображения должен быть меньше 5 МБ.');

        $Image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
        $Size = getimagesize($_FILES['avatar']['tmp_name']);
        $Tmp = imagecreatetruecolor(120, 120);
        imagecopyresampled($Tmp, $Image, 0, 0, 0, 0, 120, 120, $Size[0], $Size[1]);

        if ($_SESSION['USER_AVATAR'] == 0){
            $Files = glob ('resource/avatar/*', GLOB_ONLYDIR);
            foreach ($Files as $num => $Dir){
                $Num ++;
                $Count = sizeof(glob($Dir.'/*.*'));
                if($Count < 250){
                    $Download = $Dir.'/'.$_SESSION['USER_ID'];
                    $_SESSION['USER_AVATAR'] = $Num;
                    mysqli_query($CONNECT, "UPDATE `users` SET `avatar` = $Num WHERE `id` = $_SESSION[USER_ID]");
                    break;
                }
            }
        }
        else
            $Download = 'resource/avatar/'.$_SESSION['USER_AVATAR'].'/'.$_SESSION['USER_ID'];

        imagejpeg($Tmp, $Download.'.jpeg');
        imagedestroy($Image);
        imagedestroy($Tmp);
    }




    MessageSend(3, 'Данные успешно изменены.');
}

else if ($Module == 'adminlogin' and $_POST['enter']){

    $_POST['apassword'] = FormChars($_POST['apassword']);

    if (!$_POST['apassword'])
         MessageSend(1,'Невозможно обработать форму!');

    if ($_POST['apassword'] == "admin"){
        $_SESSION['ADMIN_LOGIN_IN'] = 1;
        MessageSend(3,'Успешный вход!', '/admin');
    }

    else
        MessageSend(1,'Неверный пароль!');




    exit(header('Location: /admin'));

}

else if ($Module == 'adminlogout' and $_SESSION['ADMIN_LOGIN_IN'] == 1){
    $_SESSION['ADMIN_LOGIN_IN'] = 0;
    exit(header('Location: /index'));
}


ULogin(0);

if ($Module == 'register' and $_POST['enter']){
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['password'] = GenPass(FormChars($_POST['password']),$_POST['login']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['country'] = FormChars($_POST['country']);



    if (!$_POST['login'] or !$_POST['email'] or !$_POST['password'] or !$_POST['name'] or !$_POST['country']>4)
        MessageSend(1,'Невозможно обработать форму!');

    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'"));
    if ($Row['login']) MessageSend(1, 'Логин <b>'.$_POST['login'].'</b> уже используется.');

    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'"));
    if ($Row['email']) MessageSend(1, 'E-Mail <b>'.$_POST['email'].'</b> уже используется.');

    mysqli_query($CONNECT, "INSERT INTO `users` VALUES ('', '$_POST[login]', '$_POST[password]', '$_POST[name]', NOW(), '$_POST[email]', '$_POST[country]', 0, 1, 0)");


     if ($_FILES['avatar']['tmp_name']){
        if ($_FILES['avatar']['type'] != 'image/jpeg')
            MessageSend(1, 'Неверный тип изображения.');
    if ($_FILES['avatar']['size'] > 5000000)
            MessageSend(1, 'Размер изображения должен быть меньше 5 МБ.');

        $Image = imagecreatefromjpeg($_FILES['avatar']['tmp_name']);
        $Size = getimagesize($_FILES['avatar']['tmp_name']);
        $Tmp = imagecreatetruecolor(120, 120);
        imagecopyresampled($Tmp, $Image, 0, 0, 0, 0, 120, 120, $Size[0], $Size[1]);

        if ($_SESSION['USER_AVATAR'] == 0){
            $Files = glob ('resource/avatar/*', GLOB_ONLYDIR);
            foreach ($Files as $num => $Dir){
                $Num ++;
                $Count = sizeof(glob($Dir.'/*.*'));
                if($Count < 250){
                    $Download = $Dir.'/'.$_SESSION['USER_ID'];
                    $_SESSION['USER_AVATAR'] = $Num;
                    mysqli_query($CONNECT, "UPDATE `users` SET `avatar` = $Num WHERE `id` = $_SESSION[USER_ID]");
                    break;
                }
            }
        }
        else
            $Download = 'resource/avatar/'.$_SESSION['USER_AVATAR'].'/'.$_SESSION['USER_ID'];

        imagejpeg($Tmp, $Download.'.jpeg');
        imagedestroy($Image);
        imagedestroy($Tmp);
    }


    MessageSend(3, "Регистрация аккаунта успешно завершена. Используйте указанные данные для входа в аккаунт.", "/login");

}

 /*=======================*/
else if ($Module == 'login' and $_POST['enter']){
    $_POST['login'] = FormChars($_POST['login']);
    $_POST['password'] = GenPass(FormChars($_POST['password']), $_POST['login']);

    if (!$_POST['login'] or !$_POST['password'])
         MessageSend(1,'Невозможно обработать форму!');
    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `password`, `active`
                                                      FROM `users` WHERE `login` = '$_POST[login]'"));
    if ($Row['password'] != $_POST['password'])
        MessageSend(1, 'Неверный логин или пароль.');
    if ($Row['active'] = 0)
        MessageSend(1, 'Аккаунт пользователя был удалён.');

    $Row = mysqli_fetch_assoc(mysqli_query($CONNECT,
                                                    "SELECT `id`, `name`, `regdate`, `email`, `country`, `avatar`, `password`, `login`
                                                    FROM `users` WHERE `login` = '$_POST[login]'"));
    $_SESSION['USER_LOGIN'] = $Row['login'];
    $_SESSION['USER_PASSWORD'] = $Row['password'];

    $_SESSION['USER_ID'] = $Row['id'];
    $_SESSION['USER_NAME'] = $Row['name'];
    $_SESSION['USER_REGDATE'] = $Row['regdate'];
    $_SESSION['USER_EMAIL'] = $Row['email'];
    $_SESSION['USER_COUNTRY'] = UserCountry($Row['country']);
    $_SESSION['USER_AVATAR'] = $Row['avatar'];
    $_SESSION['USER_LOGIN_IN'] = 1;

    if ($_REQUEST['remember']){
        setcookie('user', $_POST['password'], strtotime('+30 days'), '/');
    }



    exit(header('Location: /profile'));

}




?>


