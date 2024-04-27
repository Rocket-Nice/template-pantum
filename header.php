<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="description" content="description">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="canonical" href="<?php echo get_permalink(); ?>" />

    <?php wp_head(); ?>
</head>

<body>
    <?php
    // Получаем ID главной страницы
    $frontPageID = get_option('page_on_front');

    // Содержимое "Опции и расходные материалы"
    $optionsContentTitle = get_field('options_content_of_title', $frontPageID);

    // Содержимое "Сервис и поддержка"
    $serviceContentTitle = get_field('service_content_of_title', $frontPageID);

    // Содержимое "О компании"
    $aboutContentTitle = get_field('about_company_content_of_title', $frontPageID);

    // Содержимое "Товары"
    $productsContentTitle = get_field('header_products_title', $frontPageID);

    // Добавление класса light на главной страницы для шапки
    $header_class = '';
    if (is_front_page()) {
        $header_class = 'light';
    }

    // Активная страница
    $current_page_id = get_queried_object_id();
    ?>

    <div class="indheader <?= $header_class ?>">
        <header class="header">
            <a href="/" class="logo fl dpb"><img src="/wp-content/themes/pantum/assets/img/logo.png" class="logo1" width='140' height='16'></a>
            <div class="top_right fr">
                <div class="search fl"></div>
                <div class="search_fixed" style="">
                    <div class="wrap1 hid">
                        <input type="text" placeholder="Что будем искать?" id="topSearchVal" class="fl">
                        <a href='/rezultaty-poiska/' class="search_close fr"></a>
                    </div>
                </div>
                <a class="lang fl pr" href="https://global.pantum.com/">Pantum Global</a>
            </div>
            <div class="mobileMask"></div>
            <div class="mainMenu">
                <div class="item  hasmenu">
                    <?php if ($productsContentTitle) : ?>
                        <a class="mNav <?php if (is_page('products') || is_tree('products')) echo 'active'; ?>" href="/products/">Товары</a>
                        <div class="subMenu">
                            <div class="wrap">
                                <?php foreach ($productsContentTitle as $item) : ?>
                                    <dl>
                                        <dt><a href="<?= $item['products_link_of_type']; ?>"><?= $item['products_title']; ?></a></dt>
                                        <dd>
                                            <?php foreach ($item['content_of_the_product_type'] as $items) : ?>
                                                <a href="<?= $items['product_link_of_type']; ?>"><?= $items['type_of_product']; ?></a>
                                            <?php endforeach; ?>
                                        </dd>
                                    </dl>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="arrow"></div>
                    <?php endif; ?>
                </div>
                <div class="item hasmenu">
                    <?php if ($optionsContentTitle) : ?>
                        <a class="mNav <?php if (is_page('options-and-supplies') || is_tree('options-and-supplies')) echo 'active'; ?>" href="/consumables/">Опции и расходные материалы</a>
                        <div class="subMenu">
                            <div class="wrap">
                                <?php foreach ($optionsContentTitle as $item) : ?>
                                    <a href="<?= $item["option_content_link"]; ?>"><span><?= $item["option_content"]; ?></span></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="arrow"></div>
                    <?php endif; ?>
                </div>
                <div class="item  hasmenu">
                    <?php if ($serviceContentTitle) : ?>
                        <a class="mNav <?php if (is_page('service-and-support') || $post->post_parent == get_page_by_path('service-and-support')->ID) echo 'active'; ?>" href="/service-and-support/">Сервис и поддержка</a>
                        <div class="subMenu">
                            <div class="wrap">
                                <?php foreach ($serviceContentTitle as $item) : ?>
                                    <a href="<?= $item["service_content_link"]; ?>"><span><?= $item["service_content"]; ?></span></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="arrow"></div>
                    <?php endif; ?>
                </div>
                <div class="item  hasmenu">
                    <?php if ($aboutContentTitle) : ?>
                        <a class="mNav <?php if ((is_page('about-company') || $post->post_parent == get_page_by_path('about-company')->ID) || is_post_type_archive('news')) echo 'active'; ?>" href="/about-company/about-us/">О компании</a>
                        <div class="subMenu">
                            <div class="wrap">
                                <?php foreach ($aboutContentTitle as $item) : ?>
                                    <a href="<?= $item["about_company_content_link"]; ?>"><span><?= $item["about_company_content"]; ?></span></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="arrow"></div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mobileMenu"></div>
        </header>
    </div>