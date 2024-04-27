<?php get_header();
/* Template Name: Прошивка*/
?>

<div class="main-content">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "/wp-content/themes/pantum/assets/img/video-libr.jpg",
		'title' => 'Программное обеспечение и драйверы',
		'subTitle' => '',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => true,
	]);
	?>
	<div class="about-page-wrapper">
		<h1 class="visually-hidden">Драйверы устройств компании Pantum</h1>
		<div class="wrap1 main_wrap sidebar download">

			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list ">
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title  f-18">
							<a href="/driver/" class="sidebar-left__link">Драйверы</a>
						</h2>
					</li>

					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/manual/" class="sidebar-left__link">Руководство пользователя</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/ocr/" class="sidebar-left__link"> OCR</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/faq/" class="sidebar-left__link">FAQ</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title active f-18 ">
							<a href="/update/" class="sidebar-left__link">Прошивки</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/app/" class="sidebar-left__link">Приложения</a>
						</h2>
					</li>
				</ul>
			</div>

			<div class="sidebar__right sidebar-right bg-fff bra ">

				<div class="sidebar-right__video-search video-search download-search">

					<div class="download-search__search">
						<h2 class="video-search__title f-22 fb">Введите модель</h2>
						<div class="download-search__buttons mt15">
							<input type="text" name='download-search' id='downloadSearch' class="download-search__search-field" autocomplete="off" placeholder="Пример: P2500W">
							<input type="submit" class="download-search__search-button" value=''>
						</div>
					</div>

					<div class="download__filter mt40">
						<h2 class="video-search__title f-22 fb">Фильтр</h2>
						<div class="video-search__field mt15">
							<input type="hidden" id='moduleType' value='videos' class="video-search__input">
							<input type="hidden" id='menuId' name='menuId' class="video-search__input">

							<!-- this -->
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
					</div>
				</div>


				<div class="sidebar-right__manual firmware"></div>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>