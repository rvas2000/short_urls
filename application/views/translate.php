<?php
    defined('BASEPATH') OR exit('No direct script access allowed');
//    $a = 4;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Сокращение ссылок</title>
    </head>
    <body>

        <div id="container">
            <h1>Сокращение ссылок</h1>
            <div id="info"><?php echo $message; ?></div>
            <form method="post" action="">
                <label>Введите длинную ссылку <input type="text" name="long_url"/></label>
                <input type="submit" value="Получить короткую ссылку"/>
            </form>
            <?php if (! empty($shortUrl)):?>
                <div>
                    Короткая ссылка
                    <a href="<?php echo $shortUrl;?>" target="_blank"><?php echo $shortUrl;?></a>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>