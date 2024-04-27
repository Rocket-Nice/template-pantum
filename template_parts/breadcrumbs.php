<?php
/**
 * Общий компонент, определяющий стиль отображения текста и картинки бэкграунда.
 * @var $args
 */
[
    /** @var img $imgBg Картинка бэкграунда */
    'imgBg' => $imgBg,
    /** @var text $title Заголовок */
    'title' => $title,
    /** @var text $subTitle Подзаголовок */
    'subTitle'  => $subTitle,
    /** @var bool $textAlignLeft Текст слева */
    'textAlignLeft'  => $textAlignLeft,
    /** @var bool $fontSizeBig Большой подзаголовок или нет */
    'fontSizeBig'  => $fontSizeBig,
    /** @var bool $fontColor Цвет белый или черный. True = White */
    'fontColor'  => $fontColor,
] = $args;
?>


<div class="inbanner pr mt100 <?= $fontColor ? '' : 'inbanner__black'; ?>">
    <?php if($imgBg): ?><img class="bg_img" src="<?= $imgBg; ?>">
        <div class="ban_txt pa">
            <div class="wrap1">
                <h1 class="f-42 c-fff <?= $textAlignLeft ? '' : 'tc'; ?>"><?= $title; ?></h1>
                <div class="<?= $fontSizeBig ? 'f-24' : 'f-18'; ?> c-fff <?= $textAlignLeft ? '' : 'tc'; ?> mt10 subtitle"><?= $subTitle; ?></div>
            </div>
        </div>
    <?php endif; ?>
    <div class="crumbs_white">
        <div class="crumbs">
            <div class="wrap1">
                <?php
                // Проверяем, является ли текущая страница страницей архива "news"
                if ( is_post_type_archive('news') ) {
                    // Выводим ссылку на главную страницу
                    echo '<a href="' . home_url('/') . '">Главная</a>';
                    // Выводим разделитель
                    echo ' ';
                    // Выводим ссылку на страницу "О Компании" со ссылкой на "О нас"
                    echo '<a href="' . home_url('/about-company/about-us/') . '">О Компании</a>';
                    // Выводим разделитель
                    echo ' ';
                    // Выводим название страницы архива "news"
                    echo '<span>Новости</span>';
                } else {
                    // Проверяем, является ли текущая страница страницей "О компании"
                    if ( is_page('about-company') ) {
                        // Если это так, меняем ссылку на "О Компании" на ссылку на "О нас"
                        echo '<a href="' . home_url('/about-company/about-us/') . '">О Компании</a>';
                    } else {
                        // Выводим стандартные хлебные крошки Yoast SEO
                        if ( function_exists('yoast_breadcrumb') ) {
                            yoast_breadcrumb('<div class="breadcrumbs">','</div>');
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>