<?php get_header();
/* Template Name: Видеотека о товарах*/
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/video-libr.jpg",
		'title' => 'Видеотека',
		'subTitle' => 'Просто. Понятно. Удобно',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => true,
	]);

	get_template_part('template_parts/menuBox');
	?>
	<?php
	?>
	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Видеотeка компании Pantum</h1>
		<div class="wrap1 main_wrap sidebar videolib">
			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list">
					<li class="sidebar-left__item">
						<h2 class="sidebar-left__title f-18">
							<a href="/scene/" class="sidebar-left__link">О нас</a>
						</h2>
					</li>
					<li class="sidebar-left__item accordion">
						<h2 class="sidebar-left__title sidebar-left__title--drop-down accordion__control sidebar-left__title--current about-product f-18 ">
							<a href=/operation/ class="sidebar-left__link">О товаре</a>
						</h2>
						<ul class="sidebar-left__inner-list videolib__inner-list accordion__content">
							<li class="sidebar-left__inner-item">
								<div class="sidebar-left__inner-link
								videolib__inner-link">Прочие инструкции</div>
							</li>
							<li class="sidebar-left__inner-item">
								<div class="sidebar-left__inner-link videolib__inner-link">Руководство по установке драйвера </div>
							</li>
							<li class="sidebar-left__inner-item">
								<div class="sidebar-left__inner-link videolib__inner-link">Руководство по распаковке нового принтера</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="sidebar__right sidebar-right">
				<div class="sidebar-right__video-search video-search">
					<h2 class="video-search__title f-22 fb">Фильтр</h2>

					<div class="video-search__field mt15">
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
						<!-- this -->
					</div>
				</div>

				<ul class="sidebar-right__list video mt30">
					<?php
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					// Аргументы запроса
					$args = array(
						'post_type'      => 'video-post-type',
						'posts_per_page' => 6,
						'orderby' => 'date',
						'paged' => $paged,
					);

					// Создание нового экземпляра запроса
					$query = new WP_Query($args);
					ob_start();
					// Проверка, есть ли записи
					if ($query->have_posts()) {
						// Цикл по записям
						while ($query->have_posts()) {
							$query->the_post();

							// Получаем таксономию для записи
							$taxonomy = 'products-type';
							$terms = get_the_terms(get_the_ID(), $taxonomy);

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
								<li class="sidebar-right__item video-item" data-video="<?= get_field('video-library_video')['url']; ?>" data-poster="/wp-content/themes/pantum/assets/img/pantum-poster.jpg">
									<a class="video-item__video-link tran-scale ">
										<img src="/wp-content/themes/pantum/assets/img/video-product-1.jpg" class="video-item__preview-image" loading='lazy' decoding='async' width='347' height='180'>
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
							}
						}
					} else {
						// Записи не найдены
						echo 'Записи не найдены';
					}
					?>
				</ul>

				<?php
				if ($Query->found_posts > 6) {
				?>
					<section class="news-pagination">
						<ol class="news-pagination__list">
							<li class="news-pagination__item"><a class="pagination-link news-pagination__link pagination-link--prev"></a></li>
							<?php
							$max_num_pages = $query->max_num_pages;
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


<div class="modal-show-video">
	<div class="modal-show-video__wrapper ">
		<video class='modal-show-video__video' controls="" poster="" preload="auto" x-webkit-airplay="true" x5-playsinline="true" webkit-playsinline="true" playsinline="true" x5-video-orientation="portraint" x5-video-player-fullscreen="true" src="">

			<source src="" type="video/mp4">
		</video>
		<a class="modal-show-video__close">
			<img src="/wp-content/themes/pantum/assets/img/video_close.png" loading='lazy' decoding='async' width='24' height='24'>
		</a>
	</div>
</div>
<?php get_footer(); ?>