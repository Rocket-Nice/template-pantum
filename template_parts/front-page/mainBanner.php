
<section class="banner">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php if (get_field('sliders_main_banner')):
                foreach (get_field('sliders_main_banner') as $item): 
									$thumbnailMobile = wp_get_attachment_image_src($item['main_banner_bg']['ID'], 'medium');
									$thumbnailTablet = wp_get_attachment_image_src($item['main_banner_bg']['ID'], 'medium_large');
									
								?>
                    <div class="swiper-slide">
                        <a href="<?= $item['main_banner_link']; ?>">
												<picture>
													<source srcset='<?= $thumbnailMobile[0]?>' media='(max-width: 475px)'>
													<source srcset='<?= $thumbnailTablet[0]?>' media='(max-width: 1024px)'>
													<img src="<?= $item['main_banner_bg']['url']; ?>" decoding='async' width='375' height='183'>
												</picture>
                            
                        </a>
                        <div class="ban_txt pa ">
                            <div class="wrap">
                                <div class="tit1 f-18 c-64656a"><?= $item['main_banner_series']; ?></div>
                                <h2 class="tit2 f-72 c-333"><?= $item['main_banner_title']; ?></h2>
                                <div class="tit3 f-24 c-64656a"><?= $item['main_banner_subtitle']; ?></div>
                                <a href="<?= $item['main_banner_link']; ?>" class="btn_more f-20 c-fff">Подробнее<br></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; endif; ?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-left pa"></div>
        <div class="swiper-right pa"></div>
    </div>
</section>


