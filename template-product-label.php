<?php get_header();
/* Template Name: Товары. Лейбл принтеры*/
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/products-header.jpg",
		'title' => 'Постоянные нововведения',
		'subTitle' => 'Совершенство в каждой детали',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => false,
	]);

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$listQueryArgs = array(
		'post_type'      => 'products',
		'posts_per_page' => 6,
		'orderby'        => 'date',
		'paged'          => $paged
	);
	$listQueryArgs['tax_query'][] = array(
		'taxonomy' => 'type-printer',
		'field'    => 'slug',
		'terms'    => 'label-printers',
	);
	$listQuery = new WP_Query($listQueryArgs);
	?>


	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Опции и расходные материалы</h1>
		<div class="wrap1 main_wrap sidebar consumables products-center">


			<div class="sidebar__search ">
				<input class="sidebar__search-field" value id='searchVal' placeholder='Введите модель'>
				<input class='sidebar__search-button' type='submit' value = ''>
			</div>

			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list">
					<li class="sidebar-left__item">
						<h2 class="sidebar-left__title sidebar-left__title--all f-18">
							<a href="/products/" class="sidebar-left__link">Все продукты</a>
						</h2>
					</li>

					<li class="sidebar-left__item sidebar-left__item--product">
						<h2 class="sidebar-left__title sidebar-left__title--drop-down laser f-18">
							<a href="laser-devices" class="sidebar-left__link">Лазерные устройства</a>
						</h2>
					</li>

					<li class="sidebar-left__item sidebar-left__item--product">
						<h2 class="sidebar-left__title f-18 label">
							<a href="label-printers" class="sidebar-left__link">Лейбл принтеры</a>
						</h2>
					</li>

				</ul>
			</div>


			<div class="sidebar__right sidebar-right">
				<ul class="sidebar-right__select-list">
					<li class="sidebar-right__select-item--clear">
						<a class="sidebar-right__select-link--clear">Очистить все</a>
					</li>
				</ul>

				<ul class="consumables__list">
					<?php if ($listQuery->have_posts()) :
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
					else : ?>
						<div class="sidebar-right__noresult noresult">
							<img src="/wp-content/themes/pantum/assets/img/noresult-search.png" class="noresult__img">
							<p class="noresult__message f-16 c-999 tc mt35">
								Pantum усердно работает над исследованиями и разработками......
							</p>
						</div>
					<?php endif; ?>
				</ul>

				<?php
				if ($listQuery->found_posts > 6) {
				?>
					<section class="news-pagination product">
						<ol class="news-pagination__list">
							<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--prev"></a></li>
							<?php
							$max_num_pages = $listQuery->max_num_pages;
							for ($i = 1; $i <= $max_num_pages; $i++) {
								echo '<li class="news-pagination__item"><a href="" class="news-pagination__link pagination-link">' . $i . '</a></li>';
							}
							?>
							<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--next"></a></li>
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
		<h2 class="check-banner__title f-36 fb">Предложения по улучшению</h2>
		<a href="/appeal-to-pantum/" class="check-banner__link">Предложить</a>
	</div>
</section>
<?php get_footer(); ?>