<?php get_header();
/* Template Name: Сервисные центры*/
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/service-center-bg.jpg",
		'title' => 'Мы рядом',
		'subTitle' => '',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => false,
	]);
	?>


	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Сервисные центры компании Pantum</h1>
		<div class="wrap1 main_wrap sidebar service-center">

			<div class="service-center__search bg-fff bra">
				<input type="hidden" id='areaType' name='areaType' class="video-search__input">
				<input type="hidden" id='limitNum' name='limitNum' class="video-search__input">
				<input type="hidden" id='menuId' name='menuId' class="video-search__input">

				<?php
				// Получаем все родительские термины (МФУ)
				$parent_terms_region = get_terms(array(
					'taxonomy' => 'countries',
					'parent' => 0,
				));

				// Выводим родительские термины
				if (!empty($parent_terms_region)) {
					echo '<div class="video-search__select-field">';
					echo '<h3 class="video-search__select-title region-parent f-16">Выберите регион</h3>';
					echo '<div class="video-search__select-list region">';
					echo '<input type="hidden" name="modelNumber" id="modelNumber" class="video-search__select-hidden">';
					foreach ($parent_terms_region as $parent_term) {
						echo '<a class="video-search__select-item f-16">' . $parent_term->name . '</a>';
					}
					echo '</div>';
					echo '</div>';
				} ?>
				<div class="video-search__select-field">
					<h3 class="video-search__select-title f-16 gorod-parent">Выберите город</h3>
					<div class="video-search__select-list gorod">
						<input type="hidden" class="video-search__select-hidden">
					</div>
				</div>

				<input type="hidden" id='modulType' name='modulType' class="video-search__input">

				<?php
				// Получаем все родительские термины (МФУ)
				$parent_terms = get_terms(array(
					'taxonomy' => 'driver-type-product',
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

			<div class="sidebar__left sidebar-left sidebar-left--scroll-wrap">
				<div class="sidebar-left--scroll">
					<ul class="service-center__list">
						<?php
						$args = array(
							'post_type' => 'service-center',
							'posts_per_page' => -1,
						);

						$listQuery = new WP_Query($args);

						if ($listQuery->have_posts()) :
							while ($listQuery->have_posts()) :
								$listQuery->the_post();
						?>
								<li class="service-center__item" data-coords="<?= get_field('service_coordinate'); ?>">
									<h2 class="service-center__title f-18 c-000"> <span class="service-center__number"><?= $listQuery->current_post + 1; ?></span> <?= the_title(); ?> </h2>
									<p class="service-center__address f-16 c-999"><?= get_field("city_address"); ?></p>
									<p class="service-center__phone f-16 c-999"><?= get_field("service_phone"); ?></p>
									<p class="service-center__check-map f-16 c-999">Посмотреть карту</p>
								</li>
						<?php endwhile;
						endif; ?>
					</ul>
				</div>
			</div>

			<div class="sidebar__right sidebar-right">
				<div class="service-center__map-wrapper">
					<div id='map' class='service-center__map'>

					</div>

				</div>
			</div>
		</div>
	</div>
</div>




<script src="https://api-maps.yandex.ru/2.1/?apikey=e753655b-7585-4ca8-b37e-b1738c2304c7&lang=ru_RU" acync></script>
<?php get_footer(); ?>