
<?php get_header();
/* Template Name: Политика использования cookie */
?>
	<?php
		get_template_part('template_parts/breadcrumbs', null, [
			'imgBg' => "/wp-content/themes/pantum/assets/img/about-us.jpg",
			'title' => '',
			'subTitle' => '',
			'textAlignLeft' => false,
			'fontSizeBig' => false,
			'fontColor' => true,
		]);
	?>
	
<div class="main-content ptb95 bg-f9">
	<section class="privacy-policy policy wrap bg-fff bra hid">
		<h1 class="policy__title f-36 fb"><?= the_title(); ?></h1>

	<div class="policy__content c-999 mt25">
		<?= get_field('privacy_policy_cookie'); ?>
	</div>

	</section>
</div>

<?php get_footer(); ?>