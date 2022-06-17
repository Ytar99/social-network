<?PHP
    if($Param['msg_delete']){
        mysqli_query($CONNECT, "DELETE FROM `chat` WHERE `id` = $Param[msg_delete]");
        MessageSend(3, 'Сообщение удалено.');
    }

    else if($Param['user_delete']){
        mysqli_query($CONNECT, "DELETE FROM `users` WHERE `login` = '$Param[user_delete]'");
        MessageSend(3, 'Пользователь удалён.', '/admin');
    }

else if ($Param['edit'] == 1 and $_POST['aenter']){
        echo 'here';

    ALogin(1);
    $_POST['npassword'] = FormChars($_POST['npassword']);
    $_POST['name'] = FormChars($_POST['name']);
    $_POST['email'] = FormChars($_POST['email']);
    $_POST['country'] = FormChars($_POST['country']);

    if ($_POST['npassword']){
        $Password = GenPass($_POST['npassword'], $_SESSION['AUSER_LOGIN']);

        mysqli_query($CONNECT, "UPDATE `users` SET `password` = '$Password' WHERE `id` = $_SESSION[AUSER_ID]");
        $_SESSION['AUSER_PASSWORD'] = $Password;

    }

    if ($_POST['login'] != $_SESSION['AUSER_LOGIN']){
        $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `login` FROM `users` WHERE `login` = '$_POST[login]'"));
        if ($Row['login'])
            MessageSend(1, 'Логин <b>'.$_POST['login'].'</b> уже используется.');
        mysqli_query($CONNECT, "UPDATE `users` SET `login` = '$_POST[login]' WHERE `id` = $_SESSION[AUSER_ID]");
        $_SESSION['AUSER_LOGIN'] = $_POST['login'];
    }

    if ($_POST['name'] != $_SESSION['AUSER_NAME']){
        mysqli_query($CONNECT, "UPDATE `users` SET `name` = '$_POST[name]' WHERE `id` = $_SESSION[AUSER_ID]");
        $_SESSION['AUSER_NAME'] = $_POST['name'];
    }

    if ($_POST['email'] != $_SESSION['AUSER_EMAIL']){
        $Row = mysqli_fetch_assoc(mysqli_query($CONNECT, "SELECT `email` FROM `users` WHERE `email` = '$_POST[email]'"));
        if ($Row['email'])
            MessageSend(1, 'E-Mail <b>'.$_POST['email'].'</b> уже используется.');
        mysqli_query($CONNECT, "UPDATE `users` SET `email` = '$_POST[email]' WHERE `id` = $_SESSION[AUSER_ID]");
        $_SESSION['AUSER_EMAIL'] = $_POST['email'];
    }

    if (UserCountry($_POST['country']) != $_SESSION['AUSER_COUNTRY']){
        mysqli_query($CONNECT, "UPDATE `users` SET `country` = $_POST[country] WHERE `id` = $_SESSION[AUSER_ID]");
        $_SESSION['AUSER_COUNTRY'] = UserCountry($_POST['country']);
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

        if ($_SESSION['AUSER_AVATAR'] == 0){
            $Files = glob ('resource/avatar/*', GLOB_ONLYDIR);
            foreach ($Files as $num => $Dir){
                $Num ++;
                $Count = sizeof(glob($Dir.'/*.*'));
                if($Count < 250){
                    $Download = $Dir.'/'.$_SESSION['AUSER_ID'];
                    $_SESSION['AUSER_AVATAR'] = $Num;
                    mysqli_query($CONNECT, "UPDATE `users` SET `avatar` = $Num WHERE `id` = $_SESSION[AUSER_ID]");
                    break;
                }
            }
        }
        else
            $Download = 'resource/avatar/'.$_SESSION['AUSER_AVATAR'].'/'.$_SESSION['AUSER_ID'];

        imagejpeg($Tmp, $Download.'.jpeg');
        imagedestroy($Image);
        imagedestroy($Tmp);
    }




    MessageSend(3, 'Данные успешно изменены.', '/admin');

}



    else if($_POST['enter']){
        if ($_POST['search'] == '')
            MessageSend(3, 'Выведены все результаты.');
        else{

            $Query = mysqli_query($CONNECT, "SELECT * FROM `users` WHERE `login` = '$_POST[search]'");
            $Row = mysqli_fetch_assoc($Query);
            if ($Row['login']){
                $_SESSION['SEARCH_REGDATE'] = $Row['regdate'];
                $_SESSION['SEARCH_LOGIN'] = $Row['login'];
                $_SESSION['SEARCH_GROUP'] = $Row['groupp'];
                $_SESSION['SEARCH_ID'] = $Row['id'];
                MessageSend(3, 'Пользователь найден.');
            }
            else
                MessageSend(3, 'Пользователь не найден.');
        }
    }





?>
