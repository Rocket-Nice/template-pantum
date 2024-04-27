<?php

/**
 * Отключение админ панели на сайте, когда залогинен в админ панель.
 */
add_filter('show_admin_bar', '__return_false');

/**
 * Подключение всех основным стилей и скриптов.
 */
require_once('inc/script_and_styles.php');

/**
 * Включение автоматической подстановки title страниц.
 * В данном случае title прописывает плагин Yoast SEO
 * Тэг <title> в header должен отсутствовать.
 */
add_theme_support('title-tag');

// Регистрация меню
function my_theme_register_menus()
{
    register_nav_menus(
        array(
            'primary_menu' => __('Primary Menu'),
            'secondary_menu' => __('Secondary Menu')
        )
    );
}
add_action('after_setup_theme', 'my_theme_register_menus');

// Страница "Новости" переходит на архивную страницу "Новости"
add_action('init', 'custom_news_archive_rewrite');
function custom_news_archive_rewrite()
{
    add_rewrite_rule('^about-company/news/?$', 'index.php?post_type=news', 'top');
}

// Функция для определения родительской страницы, чтобы меню дочерних страниц отображались на "О компании"
function is_tree($pid)
{      // $pid - ID родительской страницы
    global $post;         // текущий пост
    if (is_page() && ($post->post_parent == $pid || is_page($pid)))
        return true;   // родительская или текущая страница
    else
        return false;  // не родительская и не текущая страница
};

// Перенаправление "О компании" на "О нас"
add_filter('template_include', 'redirect_about_us');

function redirect_about_us($template)
{
    // Slug страницы "О компании"
    $company_page_slug = 'about-company';

    // Проверяем, является ли текущая страница "О компании"
    if (is_page($company_page_slug)) {
        // Slug страницы "О нас"
        $about_us_page_slug = 'about-us';

        // Получаем ID страницы "О нас" по slug
        $about_us_page_id = get_page_by_path($about_us_page_slug)->ID;

        // Проверяем, найдена ли страница "О нас"
        if ($about_us_page_id) {
            // Путь к пользовательскому файлу шаблона
            $custom_template = get_template_directory() . '/template-about.php';

            // Проверяем существует ли файл
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
    }

    return $template;
}

add_action('template_redirect', 'custom_video_library_redirect');

function custom_video_library_redirect()
{
    // Получаем текущий URL
    $current_url = home_url($_SERVER['REQUEST_URI']);

    // Проверяем, содержит ли текущий URL нужный слаг и не содержит ли он /video-library/scene/
    if (strpos($current_url, '/video-library/') !== false && strpos($current_url, '/video-library/scene/') === false) {
        // Проверяем, не содержит ли текущий URL /video-library/operation/
        if (strpos($current_url, '/video-library/operation/') === false) {
            // Перенаправляем на новый URL с заменой слага
            $new_url = str_replace('/video-library/', '/video-library/scene/', $current_url);
            wp_redirect($new_url);
            exit;
        }
    }
}

// Глобальная переменная для хранения информации о наличии дополнительных записей
$GLOBALS['has_more_posts'] = false;
$GLOBALS['has_more_posts_product'] = false;
$GLOBALS['has_more_posts_opcii'] = false;
$GLOBALS['has_more_posts_news'] = false;
$GLOBALS['has_more_posts_drive'] = false;
$GLOBALS['has_more_posts_manual'] = false;

// Pagination News

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function load_more_posts()
{
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'paged' => $paged
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $isBgImg = get_field('news_post_img')['url'];
            echo '<li class="newsboard__item">';
            echo '<a href="' . get_permalink() . '" class="newsboard__link tran_scale">';
            echo '<img class="news-row__img" src="' . ($isBgImg ? $isBgImg : '/wp-content/themes/pantum/assets/img/pantum.jpg') . '" width="347" height="193">';
            echo '</a>';
            echo '<div class="newsboard__text">';
            echo '<a href="' . get_permalink() . '" class="newsboard__title f-24 fb">' . get_the_title() . '</a>';
            echo '<a href="' . get_permalink() . '" class="newsboard__show-more f-18 c-999 mt50">Подробнее</a>';
            echo '</div>';
            echo '</li>';
        }
    }

    wp_die();
}

// Фильтрация видеотеки
function load_selected()
{
    if ($_POST['linkUrlOperation'] === "true") {
        // Если URL соответствует операциям, расходным материалам или опциям и запасам
        if (!empty($_POST['selectedParent'])) {
            $parent_term = get_term_by('name', $_POST['selectedParent'], 'products-type');
            $child_terms = get_terms(array(
                'taxonomy' => 'products-type',
                'parent' => $parent_term->term_id,
            ));

            // Выводим дочерние термины (серии)
            if (!empty($child_terms)) {
                foreach ($child_terms as $child_term) {
                    echo '<a class="video-search__select-item series f-16">' . $child_term->name . '</a>';
                }
            }

            if (!empty($_POST['selectedChild'])) {
                $parent_term = get_term_by('name', $_POST['selectedChild'], 'products-type');
                $grandchild_terms = get_terms(array(
                    'taxonomy' => 'products-type',
                    'parent' => $parent_term->term_id,
                ));

                // Выводим дочерние термины (модели)
                if (!empty($grandchild_terms) && !empty($_POST['selectedChild'])) {
                    foreach ($grandchild_terms as $grandchild_term) {
                        echo '<a class="video-search__select-item model f-16">' . $grandchild_term->name . '</a>';
                    }
                }
            }
        }
        exit; // Останавливаем выполнение скрипта после отправки HTML-разметки
    }
    if ($_POST['linkUrlDrive'] === "true") {
        ob_start();

        if (!empty($_POST['selectedParent'])) {
            $parent_term = get_term_by('name', $_POST['selectedParent'], 'driver-type-product');
            $child_terms = get_terms(array(
                'taxonomy' => 'driver-type-product',
                'parent' => $parent_term->term_id,
            ));

            // Выводим дочерние термины (серии)
            if (!empty($child_terms)) {
                foreach ($child_terms as $child_term) {
                    echo '<a class="video-search__select-item series f-16">' . $child_term->name . '</a>';
                }
            }

            if (!empty($_POST['selectedChild'])) {
                $parent_term = get_term_by('name', $_POST['selectedChild'], 'driver-type-product');
                $grandchild_terms = get_terms(array(
                    'taxonomy' => 'driver-type-product',
                    'parent' => $parent_term->term_id,
                ));

                // Выводим дочерние термины (модели)
                if (!empty($grandchild_terms) && !empty($_POST['selectedChild'])) {
                    foreach ($grandchild_terms as $grandchild_term) {
                        echo '<a class="video-search__select-item model f-16">' . $grandchild_term->name . '</a>';
                    }
                }
            }
        }

        $outputFilterMore = ob_get_clean();

        wp_reset_postdata();

        wp_send_json_success(array('outputFilterMore' => $outputFilterMore,));
        wp_die();
    }
}

add_action('wp_ajax_load_selected', 'load_selected');
add_action('wp_ajax_nopriv_load_selected', 'load_selected');

// Сайдбар выбор у видеотеки
function load_sidebar_video()
{

    // Флаг для указания наличия записей
    global $has_more_posts;
    $total_pages = 0;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    ob_start();

    // Аргументы запроса
    $args = array(
        'post_type'      => 'video-post-type',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'paged' => $paged,
    );

    // Проверяем, есть ли значение в $_POST['changeSeries'] и $_POST['changeSidebar']
    if (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '' && isset($_POST['changeSidebar']) && $_POST['changeSidebar'] !== '') {
        // Если оба значения выбраны, добавляем оба условия в tax_query
        $args['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'products-type',
                'field'    => 'name',
                'terms'    => $_POST['changeSeries'],
            ),
            array(
                'taxonomy' => 'slug_video_library',
                'field'    => 'name',
                'terms'    => $_POST['changeSidebar'],
            )
        );
    } elseif (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') {
        // Если только значение $_POST['changeSeries'] выбрано, добавляем только его условие в tax_query
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'products-type',
                'field'    => 'name',
                'terms'    => $_POST['changeSeries'],
            )
        );
    } elseif (isset($_POST['changeSidebar']) && $_POST['changeSidebar'] !== '') {
        // Если только значение $_POST['changeSidebar'] выбрано, добавляем только его условие в tax_query
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'slug_video_library',
                'field'    => 'name',
                'terms'    => $_POST['changeSidebar'],
            )
        );
    }

    // Если значение $_POST['changeModel'] указано
    if (isset($_POST['changeModel']) && $_POST['changeModel'] !== '') {
        // Получаем дочерние элементы выбранного термина в таксономии 'products-type'
        $taxonomy = 'products-type';
        $selected_model = $_POST['changeModel'];
        $selected_term = get_term_by('name', $selected_model, $taxonomy);

        if ($selected_term && !is_wp_error($selected_term)) {
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $selected_term->term_id,
            );
        } else {
            // Если указанный термин не существует, то не выводим посты
            $args['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'name',
                'terms'    => 'non-existent-term', // Термин, который точно не существует
            );
        }
    }

    // Создание нового экземпляра запроса
    $query = new WP_Query($args);

    // Проверка, есть ли записи
    if ($query->have_posts()) {
        // Устанавливаем флаг, что есть записи
        $has_more_posts = true;
        // Цикл по записям
        while ($query->have_posts()) {
            $query->the_post();
            // Получаем таксономию для записи
            $taxonomy = 'products-type';
            $terms = get_the_terms(get_the_ID(), $taxonomy);
            // Проверяем, есть ли термин
            if (!empty($terms) && !is_wp_error($terms) && $has_more_posts) {
                $term = reset($terms); // Берем первый термин, так как нам нужно только одно значение
                // Получаем родительский термин
                $parent_term = null;
                if ($term->parent) {
                    $parent_term = get_term($term->parent, $taxonomy);
                }
                // Вывод информации о записи
?>
                <li class="sidebar-right__item video-item" data-video="<?= get_field('video-library_video')['url']; ?>" data-poster="/wp-content/themes/pantum/assets/img/pantum-poster.jpg">
                    <a class="video-item__video-link tran-scale ">
                        <img src="/wp-content/themes/pantum/assets/img/video-product-1.jpg" class="video-item__preview-image">
                        <div class="video-item__video-mask">
                            <div class="video-item__video-play-btn"></div>
                        </div>
                    </a>
                    <div class="video-item__text-content">
                        <h2 class="video-item__title f-16 fb"><?= the_title(); ?></h2>
                        <p class="video-item__info f-14 c-999 mt10">
                            <?= ($parent_term) ? $parent_term->name : ''; ?>
                            <br>
                            <?php
                            // Вывод дочерних элементов термина через "/"
                            if ($parent_term) {
                                $children = get_term_children($parent_term->term_id, $taxonomy);
                                if (!empty($children)) {
                                    $child_names = array();
                                    foreach ($children as $child_id) {
                                        $child_term = get_term_by('id', $child_id, $taxonomy);
                                        // Проверяем, выбран ли текущий дочерний элемент
                                        if (has_term($child_id, $taxonomy, get_the_ID())) {
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Модель ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                    }
                                    // Если выбран хотя бы один дочерний элемент, выводим их
                                    if (!empty($child_names)) {
                                        echo 'Модели: ' . implode(' / ', $child_names);
                                    } else {
                                        // Если ни один дочерний элемент не выбран, выводим все
                                        foreach ($children as $child_id) {
                                            $child_term = get_term_by('id', $child_id, $taxonomy);
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Модель ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                        echo 'Модели: ' . implode(' / ', $child_names);
                                    }
                                }
                            }
                            ?>
                        </p>
                        <p class="video-item__date f-14 c-999 mt20"><?php echo get_the_date('Y.m.d'); ?></p>
                    </div>
                </li>
            <?php
                // Добавляем задержку в 300 миллисекунд
                usleep(100000);
            }
        }
    } else {
        // Записи не найдены
        echo 'Записи не найдены';
        $has_more_posts = false;
    }

    $total_pages = ($query->max_num_pages > 0) ? $query->max_num_pages : 0; // Получаем общее количество страниц

    $output = ob_get_clean();

    // Pagination
    ob_start();

    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }

    $outputPagination = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts и общее количество страниц
    wp_send_json_success(array('has_more_posts' => $has_more_posts, 'html' => $output, 'htmlPagination' => $outputPagination, 'total_pages' => $total_pages));
}

add_action('wp_ajax_load_sidebar_video', 'load_sidebar_video');
add_action('wp_ajax_nopriv_load_sidebar_video', 'load_sidebar_video');

// Product load

function load_sidebar_product()
{
    // Флаг для указания наличия записей
    global $has_more_posts_product;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    // products this

    ob_start();

    $total_pages_products = 0;
    $printer_type_string = explode(',', $_POST['printer_type']);
    $printer_type_string = array_filter(array_map('trim', $printer_type_string));

    $listQueryArgs = array(
        'post_type'      => 'products',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'paged'          => $paged
    );

    if (empty($_POST['searchValue']) || $_POST['searchValue'] === "") {
        if (!empty($printer_type_string) && $printer_type_string[0] !== "") {
            $listQueryArgs['tax_query'] = array(
                array(
                    'taxonomy' => 'filter-type',
                    'field'    => 'slug',
                    'terms'    => array_map('sanitize_text_field', $printer_type_string),
                )
            );
        } else {
            $current_url = $_POST['currentPageUrl'];

            if (strpos($current_url, 'laser-devices') !== false) {
                $listQueryArgs['tax_query'] = array(
                    array(
                        'taxonomy' => 'type-printer',
                        'field'    => 'slug',
                        'terms'    => 'laser-devices',
                    )
                );
            }
        }
    } else {
        $listQueryArgs = array_merge($listQueryArgs, array('s' => $_POST['searchValue']));
    }

    $listQuery = new WP_Query($listQueryArgs);

    if ($listQuery->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_product = true;
        while ($listQuery->have_posts()) :
            $listQuery->the_post();

            // Получаем значение поля products_description_img для текущего поста
            $product_image_url = get_field('products_description_img');

            // Проверяем, что значение получено и не пустое
            if (!empty($product_image_url)) {
                $image_url = $product_image_url['url'];
            }
            ?>
            <li class="consumables__item product-card">
                <a href="<?= get_post_permalink() ?>" class="product-card__link">
                    <img src="<?= $image_url; ?>" class="product-card__image">
                </a>
                <div class="product-card__text">
                    <a href="<?= get_post_permalink() ?>" class="product-card__title f-18 "><?= the_title(); ?></a>
                    <p class="product-card__model f-16 c-999"></p>
                    <p class="product-card__price f-18 c-999 mt5"></p>
                </div>
                <div class="product-card__mask"></div>
            </li>
        <?php endwhile;
    else :
        $has_more_posts_product = false; ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Pantum усердно работает над исследованиями и разработками......
            </p>
        </div>
        <?php endif;

    $total_pages_products = $listQuery->max_num_pages; // Получаем общее количество страниц
    $total_posts_products = $listQuery->found_posts; // Получаем общее количество постов

    $outputProducts = ob_get_clean();

    // Pagination
    ob_start();

    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--prev"></a></li>';
    for ($i = 1; $i <= $total_pages_products; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }
    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--next"></a></li>';

    $outputPaginationProd = ob_get_clean();

    ob_start();

    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--prev"></a></li>';
    for ($i = 1; $i <= $total_pages_products; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }
    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--next"></a></li>';

    $outputPaginationProdD = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts_product и общее количество страниц
    wp_send_json_success(array('has_more_posts_product' => $has_more_posts_product, 'outputProducts' => $outputProducts, 'outputPaginationProdD' => $outputPaginationProdD, 'htmlPaginationProd' => $outputPaginationProd, 'total_pages_products' => $total_pages_products, 'total_posts_products' => $total_posts_products));
}

add_action('wp_ajax_load_sidebar_product', 'load_sidebar_product');
add_action('wp_ajax_nopriv_load_sidebar_product', 'load_sidebar_product');


// Опции и расходные материалы
function load_sidebar_opcii()
{
    // Флаг для указания наличия записей
    global $has_more_posts_opcii;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    // consumables this

    ob_start();

    $total_pages_opcii = 0;
    $printer_type_string = explode(',', $_POST['opcii_type']);
    $printer_type_string = array_filter(array_map('trim', $printer_type_string));

    $listQueryArgss = array(
        'post_type'      => 'consumables',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'paged'          => $paged
    );

    if (empty($_POST['searchValue']) || $_POST['searchValue'] === "") {
        if (!empty($printer_type_string) && $printer_type_string[0] !== "") {
            $listQueryArgss['tax_query'] = array(
                array(
                    'taxonomy' => 'filter-option-type',
                    'field'    => 'slug',
                    'terms'    => array_map('sanitize_text_field', $printer_type_string),
                )
            );
        } elseif (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') {
            $listQueryArgss['tax_query'] = array(
                array(
                    'taxonomy' => 'products-type',
                    'field'    => 'name',
                    'terms'    => $_POST['changeSeries'],
                )
            );
        } elseif ((isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') && (!empty($printer_type_string) && $printer_type_string[0] !== "")) {
            $listQueryArgss['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'products-type',
                    'field'    => 'name',
                    'terms'    => $_POST['changeSeries'],
                ),
                array(
                    'taxonomy' => 'filter-option-type',
                    'field'    => 'slug',
                    'terms'    => array_map('sanitize_text_field', $printer_type_string),
                )
            );
        } else {
            $listQueryArgss['tax_query'] = array(
                array(
                    'taxonomy' => 'page-option-type',
                    'field'    => 'slug',
                    'terms'    => 'options-and-supplies',
                )
            );
        }

        // Если значение $_POST['changeModel'] указано
        if (isset($_POST['changeModel']) && $_POST['changeModel'] !== '') {
            // Получаем дочерние элементы выбранного термина в таксономии 'products-type'
            $taxonomy = 'products-type';
            $selected_model = $_POST['changeModel'];
            $selected_term = get_term_by('name', $selected_model, $taxonomy);

            if ($selected_term && !is_wp_error($selected_term)) {
                $listQueryArgss['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $selected_term->term_id,
                );
            } else {
                // Если указанный термин не существует, то не выводим посты
                $listQueryArgss['tax_query'][] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'name',
                    'terms'    => 'non-existent-term', // Термин, который точно не существует
                );
            }
        }
    } else {
        $listQueryArgss = array_merge($listQueryArgss, array('s' => $_POST['searchValue']));
    }


    $listQueryy = new WP_Query($listQueryArgss);

    if ($listQueryy->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_opcii = true;
        while ($listQueryy->have_posts()) :
            $listQueryy->the_post();
            // Получаем таксономию для записи
            $taxonomy = 'products-type';
            $terms = get_the_terms(get_the_ID(), $taxonomy);

            // Получаем значение поля products_description_img для текущего поста
            $product_image_url = get_field('products_description_img');

            // Проверяем, что значение получено и не пустое
            if (!empty($product_image_url)) {
                $image_url = $product_image_url['url'];
            }

            // Проверяем, есть ли термин
            if (!empty($terms) && !is_wp_error($terms)) {
                $term = reset($terms); // Берем первый термин, так как нам нужно только одно значение
                // Получаем родительский термин
                $parent_term = null;
                if ($term->parent) {
                    $parent_term = get_term($term->parent, $taxonomy);
                }
                // Вывод информации о записи
        ?>
                <li class="consumables__item product-card">
                    <a href="<?= get_post_permalink() ?>" class="product-card__link">
                        <img src="<?= $image_url; ?>" class="product-card__image" decoding='async' width='152' height='122'>
                    </a>
                    <div class="product-card__text">
                        <a href="<?= get_post_permalink() ?>" class="product-card__title f-18 "><?= the_title(); ?></a>
                        <p class="product-card__model f-16 c-999">
                            <?php
                            // Вывод дочерних элементов термина через "/"
                            if ($parent_term) {
                                $children = get_term_children($parent_term->term_id, $taxonomy);
                                if (!empty($children)) {
                                    $child_names = array();
                                    foreach ($children as $child_id) {
                                        $child_term = get_term_by('id', $child_id, $taxonomy);
                                        // Проверяем, выбран ли текущий дочерний элемент
                                        if (has_term($child_id, $taxonomy, get_the_ID())) {
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Модель ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                    }
                                    // Если выбран хотя бы один дочерний элемент, выводим их
                                    if (!empty($child_names)) {
                                        echo 'Для устройств: ' . implode(' / ', $child_names);
                                    } else {
                                        // Если ни один дочерний элемент не выбран, выводим все
                                        foreach ($children as $child_id) {
                                            $child_term = get_term_by('id', $child_id, $taxonomy);
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Для устройств: ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                        echo 'Для устройств: ' . implode(' / ', $child_names);
                                    }
                                }
                            }
                            ?>
                        </p>
                        <p class="product-card__price f-18 c-999 mt5"></p>
                    </div>
                    <div class="product-card__mask"></div>
                </li>
        <?php }
        endwhile;
    else :
        $has_more_posts_opcii = false; ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Pantum усердно работает над исследованиями и разработками......
            </p>
        </div>
        <?php endif;

    $total_pages_opcii = $listQueryy->max_num_pages; // Получаем общее количество страниц
    $total_posts_opcii = $listQueryy->found_posts; // Получаем общее количество постов
    $outputOpcii = ob_get_clean();

    // Pagination
    ob_start();
    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--prev"></a></li>';
    for ($i = 1; $i <= $total_pages_opcii; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }
    echo '<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--next"></a></li>';
    $htmlPaginationOpcii = ob_get_clean();

    ob_start();
    for ($i = 1; $i <= $total_pages_opcii; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }
    $htmlPaginationOpciiS = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts_opcii и общее количество страниц
    wp_send_json_success(array('htmlPaginationOpciiS' => $htmlPaginationOpciiS, 'total_posts_opcii' => $total_posts_opcii, 'has_more_posts_opcii' => $has_more_posts_opcii, 'outputOpcii' => $outputOpcii, 'htmlPaginationOpcii' => $htmlPaginationOpcii, 'total_pages_opcii' => $total_pages_opcii));
}

add_action('wp_ajax_load_sidebar_opcii', 'load_sidebar_opcii');
add_action('wp_ajax_nopriv_load_sidebar_opcii', 'load_sidebar_opcii');

// News load
function load_sidebar_news()
{
    // Флаг для указания наличия записей
    global $has_more_posts_news;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    ob_start();

    $total_pages_news = 0;

    $listQueryArgss = array(
        'post_type'      => 'news',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'paged'          => $paged
    );

    if (!empty($_POST['searchValue']) || $_POST['searchValue'] !== "") {
        $listQueryArgss = array_merge($listQueryArgss, array('s' => $_POST['searchValue']));
    }


    $listQueryy = new WP_Query($listQueryArgss);

    if ($listQueryy->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_news = true;
        while ($listQueryy->have_posts()) :
            $listQueryy->the_post();
        ?>
            <li class="consumables__item search-page__news-item">
                <a href="<?= get_post_permalink() ?>" class="search-page__news-link f-24 ellipsis">
                    <?= the_title(); ?>
                </a>

            </li>
        <?php
        endwhile;
    else :
        $has_more_posts_news = false; ?>

        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/nodata1.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php endif;

    $total_pages_news = $listQueryy->max_num_pages; // Получаем общее количество страниц
    $total_posts_news = $listQueryy->found_posts; // Получаем общее количество постов
    $outputNews = ob_get_clean();

    // Pagination
    ob_start();

    for ($i = 1; $i <= $total_pages_news; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }

    $htmlPaginationNews = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts_opcii и общее количество страниц
    wp_send_json_success(array('total_posts_news' => $total_posts_news, 'has_more_posts_news' => $has_more_posts_news, 'outputNews' => $outputNews, 'htmlPaginationNews' => $htmlPaginationNews, 'total_pages_news' => $total_pages_news));
}

add_action('wp_ajax_load_sidebar_news', 'load_sidebar_news');
add_action('wp_ajax_nopriv_load_sidebar_news', 'load_sidebar_news');

// Drive load
function load_sidebar_drive()
{
    // Флаг для указания наличия записей
    global $has_more_posts_drive;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    ob_start();

    $total_pages_drive = 0;

    $listQueryArgss = array(
        'post_type'      => 'drivers-type',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'paged'          => $paged
    );

    if (!empty($_POST['searchValue']) || $_POST['searchValue'] !== "") {
        $listQueryArgss = array_merge($listQueryArgss, array('s' => $_POST['searchValue']));
    }


    $listQueryy = new WP_Query($listQueryArgss);
    echo '<ul class="manual__list driver search-page__news-list drive">';
    if ($listQueryy->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_drive = true;

        while ($listQueryy->have_posts()) :
            $listQueryy->the_post();
        ?>
            <li class="manual__item">
                <div class="manual__drivers-wrapper">
                    <h2 class="manual__title  f-24 fb"><?= the_title(); ?></h2>
                    <div class="manual__subtext  mt15 c-999">
                        <?= get_field('driver_desc'); ?>
                    </div>
                </div>
                <a href="<?= get_field('download_drive'); ?>" class="manual__download" download="">Скачать</a>
            </li>
        <?php
        endwhile;
        echo '</ul>';
    else :
        $has_more_posts_drive = false; ?>

        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/nodata1.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php endif;

    $total_pages_drive = $listQueryy->max_num_pages; // Получаем общее количество страниц
    $total_posts_drive = $listQueryy->found_posts; // Получаем общее количество постов
    $outputDrive = ob_get_clean();

    // Pagination
    ob_start();

    for ($i = 1; $i <= $total_pages_drive; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }

    $htmlPaginationDrive = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts_opcii и общее количество страниц
    wp_send_json_success(array('total_posts_drive' => $total_posts_drive, 'has_more_posts_drive' => $has_more_posts_drive, 'outputDrive' => $outputDrive, 'htmlPaginationDrive' => $htmlPaginationDrive, 'total_pages_drive' => $total_pages_drive));
}

add_action('wp_ajax_load_sidebar_drive', 'load_sidebar_drive');
add_action('wp_ajax_nopriv_load_sidebar_drive', 'load_sidebar_drive');


// Manualload
function load_sidebar_manualS()
{
    // Флаг для указания наличия записей
    global $has_more_posts_manual;
    // Инициализируем $paged
    $paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

    ob_start();

    $total_pages_manual = 0;

    $listQueryArgss = array(
        'post_type'      => 'manual-user',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'paged'          => $paged
    );

    if (!empty($_POST['searchValue']) || $_POST['searchValue'] !== "") {
        $listQueryArgss = array_merge($listQueryArgss, array('s' => $_POST['searchValue']));
    }


    $listQueryy = new WP_Query($listQueryArgss);

    if ($listQueryy->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_manual = true;

        while ($listQueryy->have_posts()) :
            $listQueryy->the_post();
        ?>
            <li class="manual__item">
                <h2 class="manual__title f-24 fb"><?= the_title(); ?></h2>
                <p class="manual__language c-999">Язык: <?= get_field("manual_language"); ?></p>
                <a href="<?= get_field("manual_file"); ?>" class="manual__download" download>Скачать</a>
            </li>
        <?php
        endwhile;

    else :
        $has_more_posts_manual = false; ?>

        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/nodata1.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
    <?php endif;

    $total_pages_manual = $listQueryy->max_num_pages; // Получаем общее количество страниц
    $total_posts_manual = $listQueryy->found_posts; // Получаем общее количество постов
    $outputManual = ob_get_clean();

    // Pagination
    ob_start();

    for ($i = 1; $i <= $total_pages_manual; $i++) {
        echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
        // Добавляем задержку в 300 миллисекунд
        usleep(100000);
    }

    $htmlPaginationManual = ob_get_clean();

    wp_reset_postdata();

    // Возвращаем данные в формате JSON, включая флаг has_more_posts_opcii и общее количество страниц
    wp_send_json_success(array('total_posts_manual' => $total_posts_manual, 'has_more_posts_manual' => $has_more_posts_manual, 'outputManual' => $outputManual, 'htmlPaginationManual' => $htmlPaginationManual, 'total_pages_manual' => $total_pages_manual));
}

add_action('wp_ajax_load_sidebar_manualS', 'load_sidebar_manualS');
add_action('wp_ajax_nopriv_load_sidebar_manualS', 'load_sidebar_manualS');

// Руководство пользователя
function load_sidebar_manual()
{
    ob_start();

    $modelChange = $_POST['changeModel'];
    $listQueryArgss = array(
        'post_type'      => 'manual-user',
        'posts_per_page' => 1,
        'orderby'        => 'date',
    );

    if (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') {
        $modelChange = '';
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $_POST['changeSeries'],
            )
        );
    } else {
        $modelChange = $_POST['changeModel'];
    }

    // Если значение $_POST['changeModel'] указано
    if (isset($modelChange) && $modelChange !== '') {
        // Получаем дочерние элементы выбранного термина в таксономии 'driver-type-product'
        $taxonomy = 'driver-type-product';
        $selected_model = $_POST['changeModel'];
        $selected_term = get_term_by('name', $selected_model, $taxonomy);

        if ($selected_term && !is_wp_error($selected_term)) {
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $selected_term->term_id,
            );
        } else {
            // Если указанный термин не существует, то не выводим посты
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'name',
                'terms'    => 'non-existent-term', // Термин, который точно не существует
            );
        }
    }

    $listQuery = new WP_Query($listQueryArgss);

    if ($listQuery->have_posts()) : ?>
        <div class="sidebar-right__manual manual">
            <ul class="manual__list mt30">
                <?php while ($listQuery->have_posts()) :
                    $listQuery->the_post(); ?>
                    <li class="manual__item">
                        <h2 class="manual__title f-24 fb"><?= the_title(); ?></h2>
                        <p class="manual__language c-999">Язык: <?= get_field("manual_language"); ?></p>
                        <a href="<?= get_field("manual_file"); ?>" class="manual__download" download>Скачать</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
    <?php endif;

    $outputManual = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputManual' => $outputManual));
}

add_action('wp_ajax_load_sidebar_manual', 'load_sidebar_manual');
add_action('wp_ajax_nopriv_load_sidebar_manual', 'load_sidebar_manual');

// Прошивки
function load_sidebar_update()
{
    ob_start();

    $modelChange = $_POST['changeModel'];
    $listQueryArgss = array(
        'post_type'      => 'firmware',
        'posts_per_page' => 1,
        'orderby'        => 'date',
    );

    if (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') {
        $modelChange = '';
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $_POST['changeSeries'],
            )
        );
    } else {
        $modelChange = $_POST['changeModel'];
    }

    // Если значение $_POST['changeModel'] указано
    if (isset($modelChange) && $modelChange !== '') {
        // Получаем дочерние элементы выбранного термина в таксономии 'driver-type-product'
        $taxonomy = 'driver-type-product';
        $selected_model = $_POST['changeModel'];
        $selected_term = get_term_by('name', $selected_model, $taxonomy);

        if ($selected_term && !is_wp_error($selected_term)) {
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $selected_term->term_id,
            );
        } else {
            // Если указанный термин не существует, то не выводим посты
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'name',
                'terms'    => 'non-existent-term', // Термин, который точно не существует
            );
        }
    }

    $listQuery = new WP_Query($listQueryArgss);

    if ($listQuery->have_posts()) : ?>
        <div class="sidebar-right__manual manual">
            <ul class="manual__list mt30">
                <?php while ($listQuery->have_posts()) :
                    $listQuery->the_post(); ?>
                    <li class="manual__item">
                        <h2 class="manual__title f-24 fb"><?= the_title(); ?></h2>
                        <p class="manual__language c-999">Язык: <?= get_field("manual_language"); ?></p>
                        <a href="<?= get_field("manual_file"); ?>" class="manual__download" download>Скачать</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php endif;

    $outputManual = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputManual' => $outputManual));
}

add_action('wp_ajax_load_sidebar_update', 'load_sidebar_update');
add_action('wp_ajax_nopriv_load_sidebar_update', 'load_sidebar_update');

// FAQ
function load_sidebar_faq()
{
    ob_start();

    $modelChange = $_POST['changeModel'];
    $listQueryArgss = array(
        'post_type'      => 'faq-type',
        'posts_per_page' => -1,
        'orderby'        => 'date',
    );

    if (isset($_POST['changeSeries']) && $_POST['changeSeries'] !== '') {
        $modelChange = '';
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $_POST['changeSeries'],
            )
        );
    } else {
        $modelChange = $_POST['changeModel'];
    }

    // Если значение $_POST['changeModel'] указано
    if (isset($modelChange) && $modelChange !== '') {
        // Получаем дочерние элементы выбранного термина в таксономии 'driver-type-product'
        $taxonomy = 'driver-type-product';
        $selected_model = $_POST['changeModel'];
        $selected_term = get_term_by('name', $selected_model, $taxonomy);

        if ($selected_term && !is_wp_error($selected_term)) {
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $selected_term->term_id,
            );
        } else {
            // Если указанный термин не существует, то не выводим посты
            $listQueryArgss['tax_query'][] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'name',
                'terms'    => 'non-existent-term', // Термин, который точно не существует
            );
        }
    }

    $listQuery = new WP_Query($listQueryArgss);
    echo '<div class="sidebar-right__faq faq">';
    echo '<ul class="faq__list mt30">';
    if ($listQuery->have_posts()) : while ($listQuery->have_posts()) :
            $listQuery->the_post(); ?>
            <li class="faq__item">
                <a class="faq__link f-18 fb"><?= the_title(); ?></a>
                <div class="faq-popup">
                    <div class="faq-popup__close"></div>
                    <div class="faq-popup__content f-18 mt40">
                        <h3 class="faq-popup__title f-32 fb"><?= the_title(); ?></h3>
                        <p class='mt40'><?= get_field("content-faq"); ?></p>
                    </div>
                </div>
            </li>
        <?php endwhile;
    else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php echo '</ul>';
        echo '</div>';
    endif;

    $outputFaq = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputFaq' => $outputFaq));
}

add_action('wp_ajax_load_sidebar_faq', 'load_sidebar_faq');
add_action('wp_ajax_nopriv_load_sidebar_faq', 'load_sidebar_faq');

// Service
function load_sidebar_serivce()
{
    ob_start();

    $series = $_POST['changeSeries'];
    $model = $_POST['changeModel'];
    $region = $_POST['changeRegion'];
    $gorod = $_POST['changeGorod'];

    $listQueryArgss = array(
        'post_type'      => 'service-center',
        'posts_per_page' => -1,
        'orderby'        => 'date',
    );

    if (isset($region) && $region !== '') {
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'countries',
                'field'    => 'name',
                'terms'    => $region,
            )
        );
    }

    if (isset($series) && $series !== '') {
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $series,
            )
        );
    }

    if (isset($series) && $series !== '' && isset($region) && $region !== '') {
        $listQueryArgss['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $series,
            ),
            array(
                'taxonomy' => 'countries',
                'field'    => 'name',
                'terms'    => $region,
            )
        );
    }
    if (isset($series) && $series !== '' && isset($region) && $region !== '' && isset($model) && $model !== '') {
        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => array($series, $model),
            ),
            array(
                'taxonomy' => 'countries',
                'field'    => 'name',
                'terms'    => $region,
            )
        );
        $listQueryArgss['tax_query'] = $tax_query;
    }
    if (isset($series) && $series !== '' && isset($region) && $region !== '' && isset($model) && $model !== '' && isset($gorod) && $gorod !== '') {
        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => array($series, $gorod, $model),
            ),
            array(
                'taxonomy' => 'countries',
                'field'    => 'name',
                'terms'    => $region,
            )
        );
        $listQueryArgss['tax_query'] = $tax_query;
    }

    $listQuery = new WP_Query($listQueryArgss);

    if ($listQuery->have_posts()) : while ($listQuery->have_posts()) :
            $listQuery->the_post();
        ?>
            <li class="service-center__item" data-coords="<?= get_field('service_coordinate'); ?>">
                <h2 class=" service-center__title f-18 c-000"> <span class="service-center__number"><?= $listQuery->current_post + 1; ?></span> <?= the_title(); ?> </h2>
                <p class="service-center__address f-16 c-999"><?= get_field("city_address"); ?></p>
                <p class="service-center__phone f-16 c-999"><?= get_field("service_phone"); ?></p>
                <p class="service-center__check-map f-16 c-999">Посмотреть карту</p>
            </li>
        <?php endwhile;
    endif;

    $outputService = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputService' => $outputService));
}

add_action('wp_ajax_load_sidebar_serivce', 'load_sidebar_serivce');
add_action('wp_ajax_nopriv_load_sidebar_serivce', 'load_sidebar_serivce');

// search for FAQ
add_action('wp_ajax_search_faq', 'search_faq');
add_action('wp_ajax_nopriv_search_faq', 'search_faq');

function search_faq()
{
    $searchTerm = $_POST['searchTerm'];

    $args = array(
        'post_type' => 'faq-type',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );

    $listQuery = new WP_Query($args);

    ob_start();
    echo '<div class="sidebar-right__faq faq">';
    echo '<ul class="faq__list mt30">';
    if ($listQuery->have_posts()) : while ($listQuery->have_posts()) :
            $listQuery->the_post(); ?>
            <li class="faq__item">
                <a class="faq__link f-18 fb"><?= the_title(); ?></a>
                <div class="faq-popup">
                    <div class="faq-popup__close"></div>
                    <div class="faq-popup__content f-18 mt40">
                        <h3 class="faq-popup__title f-32 fb"><?= the_title(); ?></h3>
                        <p class='mt40'><?= get_field("content-faq"); ?></p>
                    </div>
                </div>
            </li>
        <?php endwhile;
    else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
    <?php echo '</ul>';
        echo '</div>';
    endif;
    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}

// search for manual
add_action('wp_ajax_search_manual', 'search_manual');
add_action('wp_ajax_nopriv_search_manual', 'search_manual');

function search_manual()
{
    $searchTerm = $_POST['searchTerm'];

    $args = array(
        'post_type' => 'manual-user',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );

    $listQuery = new WP_Query($args);

    ob_start();

    if ($listQuery->have_posts()) : ?>
        <div class="sidebar-right__manual manual">
            <ul class="manual__list mt30">
                <?php while ($listQuery->have_posts()) :
                    $listQuery->the_post(); ?>
                    <li class="manual__item">
                        <h2 class="manual__title f-24 fb"><?= the_title(); ?></h2>
                        <p class="manual__language c-999">Язык: <?= get_field("manual_language"); ?></p>
                        <a href="<?= get_field("manual_file"); ?>" class="manual__download" download>Скачать</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
    <?php endif;

    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}

// search for update
add_action('wp_ajax_search_update', 'search_update');
add_action('wp_ajax_nopriv_search_update', 'search_update');

function search_update()
{
    $searchTerm = $_POST['searchTerm'];

    $args = array(
        'post_type' => 'firmware',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );

    $listQuery = new WP_Query($args);

    ob_start();

    if ($listQuery->have_posts()) : ?>
        <div class="sidebar-right__manual manual">
            <ul class="manual__list mt30">
                <?php while ($listQuery->have_posts()) :
                    $listQuery->the_post(); ?>
                    <li class="manual__item">
                        <h2 class="manual__title f-24 fb"><?= the_title(); ?></h2>
                        <p class="manual__language c-999">Язык: <?= get_field("manual_language"); ?></p>
                        <a href="<?= get_field("manual_file"); ?>" class="manual__download" download>Скачать</a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    <?php else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php endif;

    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}

// search for opcii
add_action('wp_ajax_search_opcii', 'search_opcii');
add_action('wp_ajax_nopriv_search_opcii', 'search_opcii');

function search_opcii()
{
    $searchTerm = $_POST['searchTerm'];

    $args = array(
        'post_type' => 'consumables',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );

    $listQueryy = new WP_Query($args);

    ob_start();

    if ($listQueryy->have_posts()) :
        while ($listQueryy->have_posts()) :
            $listQueryy->the_post();
            // Получаем таксономию для записи
            $taxonomy = 'products-type';
            $terms = get_the_terms(get_the_ID(), $taxonomy);

            // Получаем значение поля products_description_img для текущего поста
            $product_image_url = get_field('products_description_img');

            // Проверяем, что значение получено и не пустое
            if (!empty($product_image_url)) {
                $image_url = $product_image_url['url'];
            }

            // Проверяем, есть ли термин
            if (!empty($terms) && !is_wp_error($terms)) {
                $term = reset($terms); // Берем первый термин, так как нам нужно только одно значение
                // Получаем родительский термин
                $parent_term = null;
                if ($term->parent) {
                    $parent_term = get_term($term->parent, $taxonomy);
                }
                // Вывод информации о записи
        ?>
                <li class="consumables__item product-card">
                    <a href="<?= get_post_permalink() ?>" class="product-card__link">
                        <img src="<?= $image_url; ?>" class="product-card__image" decoding='async' width='152' height='122'>
                    </a>
                    <div class="product-card__text">
                        <a href="<?= get_post_permalink() ?>" class="product-card__title f-18 "><?= the_title(); ?></a>
                        <p class="product-card__model f-16 c-999">
                            <?php
                            // Вывод дочерних элементов термина через "/"
                            if ($parent_term) {
                                $children = get_term_children($parent_term->term_id, $taxonomy);
                                if (!empty($children)) {
                                    $child_names = array();
                                    foreach ($children as $child_id) {
                                        $child_term = get_term_by('id', $child_id, $taxonomy);
                                        // Проверяем, выбран ли текущий дочерний элемент
                                        if (has_term($child_id, $taxonomy, get_the_ID())) {
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Модель ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                    }
                                    // Если выбран хотя бы один дочерний элемент, выводим их
                                    if (!empty($child_names)) {
                                        echo 'Для устройств: ' . implode(' / ', $child_names);
                                    } else {
                                        // Если ни один дочерний элемент не выбран, выводим все
                                        foreach ($children as $child_id) {
                                            $child_term = get_term_by('id', $child_id, $taxonomy);
                                            // Удаление слова "Модель" из имени термина
                                            $child_name = str_replace('Для устройств: ', '', $child_term->name);
                                            $child_names[] = $child_name;
                                        }
                                        echo 'Для устройств: ' . implode(' / ', $child_names);
                                    }
                                }
                            }
                            ?>
                        </p>
                        <p class="product-card__price f-18 c-999 mt5"></p>
                    </div>
                    <div class="product-card__mask"></div>
                </li>
        <?php }
        endwhile;
    else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Pantum усердно работает над исследованиями и разработками......
            </p>
        </div>
        <?php endif;


    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}

// search for product
add_action('wp_ajax_search_product', 'search_product');
add_action('wp_ajax_nopriv_search_product', 'search_product');

function search_product()
{
    $searchTerm = $_POST['searchTerm'];

    $args = array(
        'post_type' => 'products',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );

    $listQuery = new WP_Query($args);

    ob_start();

    if ($listQuery->have_posts()) :
        // Устанавливаем флаг, что есть записи
        $has_more_posts_product = true;
        while ($listQuery->have_posts()) :
            $listQuery->the_post();

            // Получаем значение поля products_description_img для текущего поста
            $product_image_url = get_field('products_description_img');

            // Проверяем, что значение получено и не пустое
            if (!empty($product_image_url)) {
                $image_url = $product_image_url['url'];
            }
        ?>
            <li class="consumables__item product-card">
                <a href="<?= get_post_permalink() ?>" class="product-card__link">
                    <img src="<?= $image_url; ?>" class="product-card__image">
                </a>
                <div class="product-card__text">
                    <a href="<?= get_post_permalink() ?>" class="product-card__title f-18 "><?= the_title(); ?></a>
                    <p class="product-card__model f-16 c-999"></p>
                    <p class="product-card__price f-18 c-999 mt5"></p>
                </div>
                <div class="product-card__mask"></div>
            </li>
        <?php endwhile;
    else :
        $has_more_posts_product = false; ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Pantum усердно работает над исследованиями и разработками......
            </p>
        </div>
        <?php endif;


    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}


// Получаем операционную систему пользователя
function get_user_os()
{
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $os_platform    =   "Unknown OS Platform";
    $os_array       =   array(
        '/windows nt 10/i'     =>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );

    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }

    return $os_platform;
}

// search for driver
add_action('wp_ajax_search_driver', 'search_driver');
add_action('wp_ajax_nopriv_search_driver', 'search_driver');

function search_driver()
{
    $searchTerm = $_POST['searchTerm'];

    // Получаем операционную систему пользователя
    $user_os = get_user_os();
    $oper = $user_os;

    // Обновляем переменную $oper
    if (isset($_POST['operations']) && $_POST['operations'] !== '') {
        $oper = ($_POST['operations'] !== $user_os) ? $_POST['operations'] : $user_os;
    }

    $args = array(
        'post_type' => 'drivers-type',
        'posts_per_page' => -1,
        's' => $searchTerm,
    );


    $args['tax_query'] = array(
        array(
            'taxonomy' => 'operation-systems-type',
            'field'    => 'name',
            'terms'    => $oper,
        )
    );

    $listQuery = new WP_Query($args);

    ob_start();

    if ($listQuery->have_posts()) : while ($listQuery->have_posts()) :
            $listQuery->the_post(); ?>
            <li class="manual__item">
                <div class="manual__drivers-wrapper">
                    <h2 class="manual__title manual__title--driver f-24 fb"><?= the_title(); ?></h2>
                    <div class="manual__subtext  mt15 c-999">
                        <?= get_field('driver_desc'); ?>
                    </div>
                </div>
                <a href="<?= get_field('download_drive'); ?>" class="manual__download" download="">Скачать</a>
            </li>
        <?php endwhile;
    else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
        <?php endif;


    wp_reset_postdata();

    $html = ob_get_clean();
    echo $html;

    die();
}

// Фильтрация драйверов
function load_selected_op()
{
    ob_start();

    if (!empty($_POST['selectedParentDrive'])) {
        $parent_term = get_term_by('name', $_POST['selectedParentDrive'], 'operation-systems-type');
        $child_terms = get_terms(array(
            'taxonomy' => 'operation-systems-type',
            'parent' => $parent_term->term_id,
        ));

        if (!empty($child_terms)) {
            foreach ($child_terms as $child_term) {
                echo '<a class="video-search__select-item version f-16">' . $child_term->name . '</a>';
            }
        }
    }

    $outputFilterDrive = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputFilterDrive' => $outputFilterDrive,));
    wp_die();
}

add_action('wp_ajax_load_selected_op', 'load_selected_op');
add_action('wp_ajax_nopriv_load_selected_op', 'load_selected_op');

// Фильтрация сервисных центров
function load_selected_service()
{
    ob_start();

    if (!empty($_POST['selectedParentService'])) {
        $parent_term = get_term_by('name', $_POST['selectedParentService'], 'countries');
        $child_terms = get_terms(array(
            'taxonomy' => 'countries',
            'parent' => $parent_term->term_id,
        ));

        if (!empty($child_terms)) {
            foreach ($child_terms as $child_term) {
                echo '<a class="video-search__select-item gorod f-16">' . $child_term->name . '</a>';
            }
        }
    }

    $outputFilterService = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputFilterService' => $outputFilterService,));
    wp_die();
}

add_action('wp_ajax_load_selected_service', 'load_selected_service');
add_action('wp_ajax_nopriv_load_selected_service', 'load_selected_service');

// Драйверы
function load_sidebar_driver()
{
    ob_start();

    // Получаем операционную систему пользователя
    $user_os = get_user_os();
    $oper = $user_os;

    // Обновляем переменную $oper
    if (isset($_POST['oper']) && $_POST['oper'] !== '') {
        $oper = ($_POST['oper'] !== $user_os) ? $_POST['oper'] : $user_os;
    }

    $series = $_POST['changeSeries'];
    $model = $_POST['changeModel'];

    $listQueryArgss = array(
        'post_type'      => 'drivers-type',
        'posts_per_page' => -1,
        'orderby'        => 'date',
    );

    $version = $_POST['versionChange'];

    if (isset($oper) && $oper !== '') {
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'operation-systems-type',
                'field'    => 'name',
                'terms'    => $oper,
            )
        );
    }

    if (isset($series) && $series !== '') {
        $listQueryArgss['tax_query'] = array(
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $series,
            )
        );
    }

    if (isset($series) && $series !== '' && isset($oper) && $oper !== '') {
        $listQueryArgss['tax_query'] = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => $series,
            ),
            array(
                'taxonomy' => 'operation-systems-type',
                'field'    => 'name',
                'terms'    => $oper,
            )
        );
    }
    if (isset($series) && $series !== '' && isset($oper) && $oper !== '' && isset($model) && $model !== '') {
        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => array($series, $model),
            ),
            array(
                'taxonomy' => 'operation-systems-type',
                'field'    => 'name',
                'terms'    => $oper,
            )
        );
        $listQueryArgss['tax_query'] = $tax_query;
    }
    if (isset($series) && $series !== '' && isset($oper) && $oper !== '' && isset($model) && $model !== '' && isset($version) && $version !== '') {
        $tax_query = array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'driver-type-product',
                'field'    => 'name',
                'terms'    => array($series, $version, $model),
            ),
            array(
                'taxonomy' => 'operation-systems-type',
                'field'    => 'name',
                'terms'    => $oper,
            )
        );
        $listQueryArgss['tax_query'] = $tax_query;
    }

    $listQuery = new WP_Query($listQueryArgss);

    if ($listQuery->have_posts()) : while ($listQuery->have_posts()) :
            $listQuery->the_post(); ?>
            <li class="manual__item">
                <div class="manual__drivers-wrapper">
                    <h2 class="manual__title manual__title--driver f-24 fb"><?= the_title(); ?></h2>
                    <div class="manual__subtext  mt15 c-999">
                        <?= get_field('driver_desc'); ?>
                    </div>
                </div>
                <a href="<?= get_field('download_drive'); ?>" class="manual__download" download="">Скачать</a>
            </li>
        <?php endwhile;
    else : ?>
        <div class="sidebar-right__noresult noresult">
            <img src="/wp-content/themes/pantum/assets/	img/noresult-search.png" class="noresult__img">
            <p class="noresult__message f-16 c-999 tc mt35">
                Запрашиваемая информация не найдена
            </p>
        </div>
    <?php endif;

    $outputDriver = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success(array('outputDriver' => $outputDriver));
}

add_action('wp_ajax_load_sidebar_driver', 'load_sidebar_driver');
add_action('wp_ajax_nopriv_load_sidebar_driver', 'load_sidebar_driver');

// photo resize thumbnail
function wps_display_attachment_settings()
{
    //    update_option( 'image_default_align', 'left' );
    //    update_option( 'image_default_link_type', 'none' );
    //    update_option( 'image_default_size', 'large' );
    // image sizes
    add_image_size('circle-post', 46, 46, array('center', 'center'));
    //
    add_image_size('main-post-preview', 500, 500);
    add_image_size('main-post-preview-mobile', 700, 0);
    //
    add_image_size('main_post_small_preview', 423, 330);
    add_image_size('main_post_small_preview_mobile', 266, 214, true);
    add_image_size('main_post_small_preview_mobile_smallest', 220, 160);
    //
    add_image_size('gold_fond_image', 390, 200);
    //
    add_image_size('right_banner_image', 150, 150);
    add_image_size('collection_circle', 164, 164, ['center', 'center']);
    //    add_image_size( 'blog-my', 683, 456 ); // Not use
    add_image_size('blog-big', 590, 9999);
    //    add_image_size( 'blog-small', 450, 9999 );
    //    add_image_size( 'blog-full', 1140, 9999, true );
    add_image_size('blog-full-th', 1140, 9999);
    //    add_image_size( 'blog-adjacent', 260, 150, true );
    //    add_image_size('blog-gall', 9999, 400,  array('center','center'));
    // Вывод изображения в карточках статьи. Пример - карточки в Пользе, или в Важное на главной.
    add_image_size('collection-image', 576, 200, ['center', 'center']);
    // В блоке посмотрите другие подборки на странице коллекций
    add_image_size('other-collections-image', 500, 310, ['center', 'center']);
    //
    add_image_size('collection-page-banner', 1032, 248, ['center', 'center']);
    add_image_size('collection-page-banner-mobile', 576, 360, ['center', 'center']);
    // Превью нового поста на главной
    add_image_size('preview-newest-post', 1412, 556, ['center', 'center']);
    add_image_size('preview-newest-post-mobile', 800, 320, ['center', 'center']);
    // Бизнес инструменты баннер выбранный вручную
    add_image_size('business-tools-preview', 780, 420, ['center', 'center']);
}

add_theme_support('post-thumbnails');

add_action('after_setup_theme', 'wps_display_attachment_settings');

// Подписаться на рассылку

// Обработка данных из формы подписки и добавление в пользовательское поле
add_action('init', 'process_subscription_form');
function process_subscription_form()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['email'])) {
            $email = sanitize_email($_POST['email']);

            // Получаем текущего пользователя
            $current_user_id = get_current_user_id();

            // Получаем текущий список подписанных адресов пользователя
            $subscribed_emails = get_user_meta($current_user_id, 'subscribed_emails', true);

            // Убедимся, что $subscribed_emails является массивом
            if (!is_array($subscribed_emails)) {
                $subscribed_emails = array();
            }

            // Добавляем новый адрес в список
            $subscribed_emails[] = $email;

            // Обновляем значение метаполя с новым списком подписанных адресов
            update_user_meta($current_user_id, 'subscribed_emails', $subscribed_emails);
        } elseif (isset($_POST['action']) && $_POST['action'] == 'delete_subscriber') {
            if (isset($_POST['subscriber_emails'])) {
                $subscriber_emails = $_POST['subscriber_emails'];

                // Получаем текущего пользователя
                $current_user_id = get_current_user_id();

                // Получаем текущий список подписанных адресов пользователя
                $subscribed_emails = get_user_meta($current_user_id, 'subscribed_emails', true);

                // Убедимся, что $subscribed_emails является массивом
                if (!is_array($subscribed_emails)) {
                    $subscribed_emails = array();
                }

                // Удаляем адреса из списка
                foreach ($subscriber_emails as $subscriber_email) {
                    $key = array_search($subscriber_email, $subscribed_emails);
                    if ($key !== false) {
                        unset($subscribed_emails[$key]);
                    }
                }

                // Обновляем значение метаполя с новым списком подписанных адресов
                update_user_meta($current_user_id, 'subscribed_emails', $subscribed_emails);

                // Возвращаем ответ сервера
                wp_send_json_success();
            }

            if (isset($_POST['subscriber_email'])) {
                $subscriber_email = sanitize_email($_POST['subscriber_email']);

                // Получаем текущего пользователя
                $current_user_id = get_current_user_id();

                // Получаем текущий список подписанных адресов пользователя
                $subscribed_emails = get_user_meta($current_user_id, 'subscribed_emails', true);

                // Убедимся, что $subscribed_emails является массивом
                if (!is_array($subscribed_emails)) {
                    $subscribed_emails = array();
                }

                // Удаляем адрес из списка
                $key = array_search($subscriber_email, $subscribed_emails);
                if ($key !== false) {
                    unset($subscribed_emails[$key]);
                }

                // Обновляем значение метаполя с новым списком подписанных адресов
                update_user_meta($current_user_id, 'subscribed_emails', $subscribed_emails);
            }
        }
    }
}


// Вывод списка подписчиков в админке
add_action('admin_menu', 'custom_admin_menu');
function custom_admin_menu()
{
    add_menu_page('Подписчики', 'Подписчики', 'manage_options', 'subscribers-list', 'display_subscribers_list');
}

function display_subscribers_list()
{
    $subscribers = get_users(array(
        'meta_key'     => 'subscribed_emails',
        'meta_compare' => 'EXISTS',
    ));

    echo '<h2 class="subscribe-inner-title">Список подписчиков</h2>';
    echo '<ul class="subscribe-inner-list">';
    foreach ($subscribers as $subscriber) {
        $emails = get_user_meta($subscriber->ID, 'subscribed_emails', true);

        // Проверяем, является ли $emails массивом
        if (is_array($emails)) {
            foreach ($emails as $email) {
                echo '<li class="subscribe-inner-item">' . '<label class="subscribe-inner-label"> <input type="checkbox" value="' . $email . '" class="subscribe-inner-input">' . $email . ' </label>' . ' <button class="delete-subscriber" data-email="' . $email . '">Удалить</button></li>';
            }
        } else {
            // Если $emails не является массивом, выводим его как строку
            echo '<li>' . $emails . ' <button class="delete-subscriber" data-email="' . $emails . '">Удалить</button></li>';
        }
    }
    echo '</ul>';

    //Кнопка удаления выбранных файлов
    echo '<button id="delete-checked">Удалить выбранные</button>';
    // Кнопка для выгрузки всех почт в Excel
    echo '<button id="export-to-excel">Выгрузить в Excel</button>';

    // JavaScript для отправки AJAX-запроса на удаление подписчика и выгрузки в Excel
    ?>
    <script>
        document.querySelectorAll('.delete-subscriber').forEach(button => {
            button.addEventListener('click', function() {
                const email = this.dataset.email;
                const data = new FormData();
                data.append('action', 'delete_subscriber');
                data.append('subscriber_email', email);
                setTimeout(() => {
                    // Перезагрузить страницу после успешного удаления
                    location.reload();
                }, 300);

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                        method: 'POST',
                        body: data
                    })
                    .then(response => {
                        if (response.ok) {
                            // Удалить элемент списка подписчиков
                            this.parentNode.remove();
                        }
                    })
                    .catch(error => console.error('Ошибка:', error));
            });
        });

        // JavaScript для удаления выбранных подписчиков
        document.getElementById('delete-checked').addEventListener('click', function() {
            const selectedEmails = [];
            document.querySelectorAll('input[type=checkbox]:checked').forEach(input => {
                selectedEmails.push(input.value);
                input.closest('.subscribe-inner-item').remove(); // Удаляем элемент из списка на клиенте
            });

            const data = new FormData();
            data.append('action', 'delete_subscriber');
            selectedEmails.forEach(email => {
                data.append('subscriber_emails[]', email);
            });

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: data
                })
                .then(response => {
                    if (response.ok) {
                        // Успешное удаление на сервере
                        console.log('Подписчики удалены успешно');
                    } else {
                        console.error('Ошибка при удалении подписчиков');
                    }
                })
                .catch(error => console.error('Ошибка:', error));
        });

        document.getElementById('export-to-excel').addEventListener('click', function() {
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: new FormData(),
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=export_subscribers_to_excel',
                })
                .then(response => {
                    if (response.ok) {
                        return response.blob();
                    } else {
                        throw new Error('Произошла ошибка при экспорте в Excel');
                    }
                })
                .then(blob => {
                    // Создаем ссылку для скачивания файла Excel
                    const url = window.URL.createObjectURL(new Blob([blob]));
                    const link = document.createElement('a');
                    link.href = url;
                    link.setAttribute('download', 'subscribers.csv');
                    document.body.appendChild(link);
                    link.click();
                    link.parentNode.removeChild(link);
                })
                .catch(error => console.error('Ошибка:', error));
        });
    </script>
    <style>
        #wpbody-content {
            padding: 20px;
        }

        .subscribe-inner-title {
            font-size: 24px;
            line-height: 36px;
            color: #2F4F4F;
        }

        .subscribe-inner-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            list-style: none;
            padding: 0;
        }

        .subscribe-inner-item {
            display: grid;
            grid-template-columns: 350px 150px;
            font-size: 18px;
            line-height: 20px;
            font-weight: 500;

            padding: 10px;

        }

        .subscribe-inner-input {
            outline: 3px solid gold;
        }

        .delete-subscriber {
            position: relative;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            color: #fff;
            background: #d2232a;
            text-transform: uppercase;
            font-size: 14px;
            margin-left: 30px;
            font-weight: 400;
            cursor: pointer;

            &:hover {
                opacity: 0.8;
            }

            &:active {
                top: 3px;
                box-shadow: 0px 3px 5px 0 #FF6347;
            }
        }


        ul.subscribers-list li {
            outline: 3px solid red;
            margin-bottom: 10px;
            font-size: 16px;
        }

        ul.subscribers-list li button.delete-subscriber {
            margin-left: 10px;
            padding: 5px 10px;
            border: none;
            background-color: #f44336;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
        }

        #delete-checked {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background: #d2232a;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 20px;
        }

        #delete-checked:hover {
            opacity: 0.8;
        }

        #export-to-excel {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
        }

        #export-to-excel:hover,
        ul.subscribers-list li button.delete-subscriber:hover {
            opacity: 0.8;
        }
    </style>

<?php
}

// Функция для экспорта всех почт в Excel
add_action('wp_ajax_export_subscribers_to_excel', 'export_subscribers_to_excel_callback');
function export_subscribers_to_excel_callback()
{
    // Получаем текущего пользователя
    $current_user_id = get_current_user_id();

    // Получаем текущий список подписанных адресов пользователя
    $subscribed_emails = get_user_meta($current_user_id, 'subscribed_emails', true);

    // Формируем данные для Excel
    $data = array();
    $data[] = array('Email', 'Дата получения');
    foreach ($subscribed_emails as $email) {
        $data[] = array($email, date('Y-m-d'));
    }

    // Формируем файл Excel
    $file = fopen('php://temp', 'w');
    foreach ($data as $row) {
        fputcsv($file, $row);
    }
    fseek($file, 0);

    // Отправляем файл Excel браузеру
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="subscribers.csv"');
    fpassthru($file);
    exit;
}

// Разрешение на загрузку файлов
ini_set('file_uploads', 'On');

// Максимально допустимый размер данных отправляемых методом POST
ini_set('post_max_size', '50M');

// Папка для хранения файлов во время загрузки
ini_set('upload_tmp_dir', 'home/user/temp');

// Максимальный размер загружаемого файла
ini_set('upload_max_filesize', '5M');

// Максимально разрешённое количество одновременно загружаемых файлов
ini_set('max_file_uploads', '10');


// Обращение в Пантум

add_action('wp_ajax_send_sug_action', 'send_sug_action');
add_action('wp_ajax_nopriv_send_sug_action', 'send_sug_action');

function send_sug_action()
{
    if (isset($_POST['action']) && $_POST['action'] === "send_sug_action") {
        // Проверяем, есть ли данные
        if (!empty($_POST['name']) && !empty($_POST['email'])) {
            // Получаем данные из формы
            $name = $_POST['name'];
            $email = $_POST['email'];
            $place = $_POST['place'];
            $message = $_POST['message'];

            // Создаем новый экземпляр объекта PHPMailer
            $mail = new \PHPMailer\PHPMailer\PHPMailer();
            $mail->CharSet = 'UTF-8';

            try {
                // Настройки SMTP (если необходимо)
                $mail->isSMTP();
                $mail->Host = 'smtp.mail.ru';
                $mail->SMTPAuth = true;
                $mail->Username = 'lisdb@bk.ru';
                $mail->Password = 'TzPsvbhkEn1taNFxaVYc';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // Установка параметров почтового сервера
                $mail->setFrom('lisdb@bk.ru', 'Your Name'); // Отправитель
                $mail->addAddress('lisdb@bk.ru', 'Recipient Name'); // Получатель
                $mail->Subject = 'Новая заявка'; // Тема письма
                $mail->Body = "Имя: $name\nEmail: $email\nГород: $place\nТекст запроса: $message"; // Текст сообщения

                // Прикрепление файлов
                $processedFiles = []; // Массив для отслеживания обработанных файлов

                foreach ($_FILES as $file) {
                    // Проверяем, был ли файл с таким именем уже обработан
                    if (!in_array($file['name'], $processedFiles)) {
                        // Если файл не был обработан, добавляем его имя в массив обработанных файлов
                        $processedFiles[] = $file['name'];
                        $mail->addAttachment($file['tmp_name'], $file['name']);
                    }
                }

                // Отправка письма
                $mail->send();
                echo 'Письмо успешно отправлено!';
            } catch (Exception $e) {
                echo "Произошла ошибка при отправке письма: {$mail->ErrorInfo}";
            }
        } else {
            echo "Не удалось получить данные из формы";
        }
    } else {
        echo "Неверный запрос";
    }
    // Завершаем выполнение PHP скрипта
    wp_die();
}
