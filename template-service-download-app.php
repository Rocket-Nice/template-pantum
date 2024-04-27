<?php get_header();
/* Template Name: Приложения*/
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
						<h2 class="sidebar-left__title f-18 ">
							<a href="/update/" class="sidebar-left__link">Прошивки</a>
						</h2>
					</li>
					<li class="sidebar-left__item ">
						<h2 class="sidebar-left__title active f-18 ">
							<a href="/app/" class="sidebar-left__link">Приложения</a>
						</h2>
					</li>
				</ul>
			</div>

			<div class="sidebar__right sidebar-right bg-fff bra ">
				<ul class="sidebar-right__ocr ocr app">
					<li class="ocr__item">
						<a href="#" class="ocr__link" download>
							<div class='ocr__img-wrapper'>
								<img src="/wp-content/themes/pantum/assets/img/app_store.png" alt="" class="app__img" >
							</div>
							<p class="ocr__text f-16 fb mt15">
							V1.3.198
							</p>
							<div class="app__qr">
								<img src="/wp-content/themes/pantum/assets/img/app_qr.png" >
							</div>
						</a>
					</li>
					<li class="ocr__item">
						<a href="#" class="ocr__link" download>
							<div class='ocr__img-wrapper'>
								<img src="/wp-content/themes/pantum/assets/img/android.png" alt="" class="app__img"  width='185' height='70'>
							</div>
							<p class="ocr__text f-16 fb mt15">
							V2.0.57
							</p>

							<div class="app__qr">
								<img src="/wp-content/themes/pantum/assets/img/android__qr.png"  decoding='async' width='140' height='140' >
							</div>
						</a>
					</li>


				</ul>
			</div>
		</div>
	</div>
</div>


<?php get_footer(); ?>