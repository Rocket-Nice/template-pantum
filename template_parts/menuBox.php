<?php
// Слаг страницы "О Компании"
$about_page_slug = 'about-company';

// Получаем текущую страницу
$current_page_id = get_the_ID();

// Получаем страницу "О Компании" по слагу
$about_page = get_page_by_path($about_page_slug);

// Если страница "О Компании" найдена
if ($about_page) {
    // Получаем список дочерних страниц страницы "О Компании"
    $child_pages = get_pages(array(
        'child_of' => $about_page->ID,
        'sort_column' => 'menu_order', // Сортируем по порядку меню
        'sort_order' => 'ASC' // По возрастанию
    ));

    // Развернем массив в обратном порядке
    $child_pages = array_reverse($child_pages);

    // Флаг, чтобы определить, нужно ли добавлять класс "active" для "Видеотека"
    $video_library_active = false;

    // Если есть дочерние страницы
    if ($child_pages) {
        // Проверяем, содержит ли текущая страница "/video-library/"
        $current_page_url = $_SERVER['REQUEST_URI'];
        if (strpos($current_page_url, '/video-library/') !== false) {
            $video_library_active = true;
        }

        // Выводим список ссылок
        echo '<div class="menu_box">';
        echo '<div class="wrap1 pr hid">';
        echo '<div class="menu_con">';
        foreach ($child_pages as $child_page) {
            $active_class = ($current_page_id == $child_page->ID) ? 'active' : ''; // Добавляем класс "active", если страница текущая
            if ($child_page->post_name == 'news' && is_post_type_archive('news')) {
                $active_class = 'active'; // Если текущая страница - архив "news", подсветим пункт меню "Новости"
            } elseif ($child_page->post_name  == "scene" || $child_page->post_name == "operation") {
                continue;
            } elseif ($child_page->post_title == "Видеотека" && $video_library_active) {
                $active_class = 'active'; // Если текущая страница содержит "/video-library/", подсветим пункт меню "Видеотека"
            }
            echo '<a href="' . get_permalink($child_page->ID) . '" class="' . $active_class . '">' . $child_page->post_title . '</a>';
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
}
?>
