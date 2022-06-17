<?php
    ULogin(1);



    if($_POST['enter'] and $_POST['text']){
        $_POST['text'] = FormChars($_POST['text']);
        mysqli_query($CONNECT, "INSERT INTO `chat` VALUES ('', '$_POST[text]', '$_SESSION[USER_LOGIN]', NOW())");
        exit(header('Location: /chat'));
    }

    Head('Чат');
?>



<body>
    <div class="wrapper">
        <header class="header"> <a href="/index"><p>Социальная сеть</p></a></header>
        <!-- .header-->
        <div class="content">
           <?php
                @Menu();
                @MessageShow();
            ?>
            <br>
            <div class="page">
                <div class="ChatBox">
                    <?php
                        $Query =  mysqli_query($CONNECT, 'SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 50');
                        while ($Row = mysqli_fetch_assoc($Query)){
                            echo '<div class="ChatBlock"><span>'.$Row['user'].' | '.$Row['time'].'</span>'.$Row['message'].'</div>';
                        }

                    ?>
                </div>

                <form method="post" action="/chat">
                    <textarea class="ChatMessage" name="text" placeholder="Введите сообщение..." required></textarea>
                    <br> <input type="submit" name="enter" value="Отправить"> <input type="reset" value="Очистить">
                </form>
            </div>

        </div>
        <!-- .content -->
        <?php Footer(); ?>
        <!-- .footer -->
    </div>
    <!-- .wrapper -->
</body>

</html>
