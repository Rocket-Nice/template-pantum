<?php get_header();
/* Template Name: Штаб квартира*/
?>
 <div class="main-content">
 	<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "/wp-content/themes/pantum/assets/img/about-shtab.jpg",
			'title' => '',
			'subTitle' => '',
			'textAlignLeft' => true,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);

		get_template_part('template_parts/menuBox');
	?>
		<section class="headquarters">
			<div class="wrap2">
			<h1 class="headquarters__title f-32 mt45">Адрес штаб-квартиры</h1>

			<div class="headquarters__address f-24 mt20 c-707070"><img src="/wp-content/themes/pantum/assets/img/headquarters-location.png" ><?= get_field('addres_text'); ?></div>

			<div class="headquarters__img-container mt45 bra hid">
				<img src="<?= get_field('img_headquarters')['url']; ?>" alt="Вид сверху на главный офис компании PANTUM" class="headquarters__image" width='347' height='260'>
			</div>
			
			</div>
		</section>
 </div>

<?php get_footer(); ?>