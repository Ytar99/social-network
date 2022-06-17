<?php Head('Главная страница');  ?>

<body>
    <div class="wrapper">
        <header class="header"> <a href="/index"><p>Социальная сеть</p></a></header>
        <!-- .header-->
        <div class="content">
           <?php
                @Menu();
                @MessageShow();
            ?>
            <div class="startpage"><p>Добро пожаловать в SOCIALNET!</p></div>

        </div>
        <!-- .content -->
        <?php Footer(); ?>
        <!-- .footer -->
    </div>
    <!-- .wrapper -->
</body>

</html>
