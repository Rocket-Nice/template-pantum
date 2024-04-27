<?php get_header();
/* Template Name: Расходные материалы и ЗИП*/
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/consumables-header.jpg",
		'title' => 'Качество, на которое можно положиться',
		'subTitle' => 'Впечатляющее качество печати с оригинальным тонером Pantum',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => false,
	]);

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$listQueryArgs = array(
		'post_type'      => 'consumables',
		'posts_per_page' => 6,
		'orderby'        => 'date',
		'paged'          => $paged
	);
	$listQuery = new WP_Query($listQueryArgs);
	?>


	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Опции и расходные материалы</h1>
		<div class="wrap1 main_wrap sidebar consumables">


			<div class="sidebar__search ">
				<input class="sidebar__search-field" value id='searchVal' placeholder='Введите модель'>
				<input class='sidebar__search-button' type='submit' value=''>
			</div>

			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list">
					<li class="sidebar-left__item">
						<h2 class="sidebar-left__title sidebar-left__title--all f-18">
							<a href="/consumables/" class="sidebar-left__link">Расходные материалы и ЗИП</a>
						</h2>
					</li>

					<li class="sidebar-left__item sidebar-left__item--product">
						<h2 class="sidebar-left__title sidebar-left__title--drop-down f-18  ">
							<a href="/options-and-supplies/" class="sidebar-left__link">Опции и расходные материалы</a>
						</h2>
					</li>
				</ul>

				<div class="consumables__device-search device-search bra bg-fff mt30">
					<h2 class="device-search__title f-18 fb">Подбор расходных материалов <span class="device-search__reset"></span></h2>

					<div class="device-search__field mt20">
						<input type="hidden" id='moduleType' value='videos' class="video-search__input">
						<input type="hidden" id='menuId' name='menuId' class="video-search__input">

						<!-- this -->
						<?php
						// Получаем все родительские термины (МФУ)
						$parent_terms = get_terms(array(
							'taxonomy' => 'products-type',
							'parent' => 0,
						));

						// Выводим родительские термины
						if (!empty($parent_terms)) {
							echo '<div class="video-search__select-field">';
							echo '<h3 class="video-search__select-title video-parent f-16">Тип</h3>';
							echo '<div class="video-search__select-list">';
							echo '<input type="hidden" name="modelNumber" id="modelNumber" class="video-search__select-hidden">';
							foreach ($parent_terms as $parent_term) {
								echo '<a class="video-search__select-item f-16">' . $parent_term->name . '</a>';
							}
							echo '</div>';
							echo '</div>';
						} ?>

						<div class="video-search__select-field">
							<h3 class="video-search__select-title series-parent f-16">Серия</h3>
							<div class="video-search__select-list series">
								<input type="hidden" name='productSeries' id='productSeries' class="video-search__select-hidden">
							</div>
						</div>

						<div class="video-search__select-field">
							<h3 class="video-search__select-title model-parent f-16">Модель</h3>
							<div class="video-search__select-list model">
								<input type="hidden" name='productModel' id='productModel' class="video-search__select-hidden">
							</div>
						</div>
					</div>
				</div>

			</div>


			<div class="sidebar__right sidebar-right">
				<ul class="sidebar-right__select-list">
					<li class="sidebar-right__select-item">
						<a class="sidebar-right__select-link--clear">Очистить все</a>
					</li>
				</ul>

				<ul class="consumables__list opcii">
					<?php if ($listQuery->have_posts()) :
						while ($listQuery->have_posts()) :
							$listQuery->the_post();
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
						<li class="sidebar-right__noresult noresult">
							<img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img" decoding='async'>
							<p class="noresult__message f-16 c-999 tc mt35">
								Pantum усердно работает над исследованиями и разработками......
							</p>
						</li>
					<?php endif; ?>
				</ul>
				<?php
				if ($listQuery->found_posts > 6) {
				?>
					<section class="news-pagination opcii">
						<ol class="news-pagination__list">
							<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--prev"></a></li>
							<?php
							$max_num_pages = $listQuery->max_num_pages;
							for ($i = 1; $i <= $max_num_pages; $i++) {
								echo '<li class="news-pagination__item"><a class="news-pagination__link pagination-link">' . $i . '</a></li>';
							}
							?>
							<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--next"></a></li>
						</ol>
						<div class="news-pagination__counter"></div>
					</section>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<section class="check-banner">
	<div class="wrap1">
		<h2 class="check-banner__title f-36 fb">Проверить подлинность расходных материалов</h2>
		<a href='/authentication/' class="check-banner__link">Проверить</a>
	</div>
</section>
<?php get_footer(); ?>