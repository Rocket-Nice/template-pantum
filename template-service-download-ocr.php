<?php get_header();
/* Template Name: OCR*/
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
						<h2 class="sidebar-left__title active f-18 ">
							<a href="/ocr/" class="sidebar-left__link"> OCR</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
							<a href="/faq/" class="sidebar-left__link">FAQ</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title f-18 ">
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
				<ul class="sidebar-right__ocr ocr">
					<li class="ocr__item">
						<a href="<?= get_field('ocr_file'); ?>" class="ocr__link" download>
							<img src="/wp-content/themes/pantum/assets/img/cdr.png" alt="" class="ocr__img" decoding='async' width='75' height='75'>
							<p class="ocr__text f-16 fb mt15">
								Используется для распознавания OCR (распознает отсканированные изображения как текст)
								<br>
								<span class="ocr__text--gray f-14 c-999">Нажмите, чтобы загрузить</span>
							</p>
						</a>
					</li>

				</ul>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>