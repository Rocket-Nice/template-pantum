<?php get_header();
/* Template Name: Страница поиска*/
?>

<div class="main-content">

	<div class="about-page-wrapper search-page mt30 pb50">
	<?php
	get_template_part('template_parts/breadcrumbs', null, [
		'imgBg' => "",
		'title' => '',
		'subTitle' => '',
		'textAlignLeft' => true,
		'fontSizeBig' => false,
		'fontColor' => false,
	]);
	?>

		<div class="site-search wrap1">
			<div class="site-search__wrapper">
				<input class='site-search__field' type="search" autocomplete='off' id='searchVal' placeholder='Что будем искать?'>
				<button class="site-search__button" id='searchSumbitButton' value='' style="border: 0;">
			</div>

		</div>

		<div class="wrap1 main_wrap sidebar download mt80">

			<div class="sidebar__left sidebar-left">
				<ul class="sidebar-left__list ">
					<li class="sidebar-left__item search-products">
						<h2 class="sidebar-left__title  f-18">
							<a class="sidebar-left__link">Товары<span class="items-counter product">(0)</span></a>
						</h2>
					</li>

					<li class="sidebar-left__item search-consumables">
						<h2 class="sidebar-left__title f-18 ">
							<a class="sidebar-left__link">Расходные материалы и аксессуары<span class="items-counter opcii">(0)</span></a>
						</h2>
					</li>
					<li class="sidebar-left__item search-news">
						<h2 class="sidebar-left__title f-18 ">
							<a class="sidebar-left__link"> Новостная <br> информация<span class="items-counter news">(0)</span></a>
						</h2>
					</li>
					<li class="sidebar-left__item accordion search-drivers">
						<h2 class="sidebar-left__title sidebar-left__title--drop-down accordion__control about-product f-18 ">
							<a class="sidebar-left__link">Драйверы<span class="items-counter driver-and-manual">(0)</span></a>
						</h2>
						<ul class="sidebar-left__inner-list videolib__inner-list accordion__content">
							<li class="sidebar-left__inner-item search-driver-button">
								<a class="sidebar-left__inner-link
								videolib__inner-link ">Драйверы</a>
							</li>
							<li class="sidebar-left__inner-item search-manual-button">
								<a class="sidebar-left__inner-link videolib__inner-link">Руководство пользователя </a>
							</li>

						</ul>
					</li>

				</ul>
			</div>


			<!-- Товары -->
			<div class="sidebar__right sidebar-right  search-page__products">

				<ul class="consumables__list laser">

				</ul>

				<section class="news-pagination product" style="display: none;">
					<ol class="news-pagination__list">

					</ol>
					<div class="news-pagination__counter"></div>
				</section>
			</div>

			<!-- Расходные материалы -->
			<div class="sidebar__right sidebar-right   search-page__consumables" style="display: none;">

				<ul class="consumables__list opcii">

				</ul>

				<section class="news-pagination opcii" style="display: none;">
					<ol class="news-pagination__list">

					</ol>
					<div class="news-pagination__counter"></div>
				</section>
			</div>

			<!-- Новости -->
			<div class="sidebar__right sidebar-right search-page__right search-page__news" style="display: none;">

				<ul class="search-page__news-list news">

				</ul>

				<section class="news-pagination news" style="display: none;">
					<ol class="news-pagination__list">

					</ol>
					<div class="news-pagination__counter"></div>
				</section>
			</div>

			<!-- Драйверы -->


			<div class="sidebar__right sidebar-right search-page__right search-page__drivers" style="display: none;">

				<div class="driver-container">
				</div>

				<section class="news-pagination drive" style="display: none;">
					<ol class="news-pagination__list">

					</ol>
					<div class="news-pagination__counter"></div>
				</section>

			</div>

			<!-- Руководство  -->

			<div class="sidebar__right sidebar-right search-page__right search-page__manual" style="display: none;">


				<ul class="manual__list manual-search search-page__news-list manual" style="display: none;">

				</ul>

				<section class="news-pagination manual" style="display: none;">
					<ol class="news-pagination__list">
						<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--prev"></a></li>

						<li class="news-pagination__item"><a href="" class="pagination-link news-pagination__link pagination-link--next"></a></li>
					</ol>
					<div class="news-pagination__counter"></div>
				</section>
			</div>

		</div>
	</div>
</div>


<?php get_footer(); ?>