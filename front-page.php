<?php get_header(); ?>

<main class="main-content">
    <?php get_template_part('template_parts/front-page/mainBanner') ?>
    <?php get_template_part('template_parts/front-page/inFuncBox') ?>
    <?php get_template_part('template_parts/front-page/inServiceBox') ?>
		<?php get_template_part('template_parts/front-page/products') ?>
		<?php get_template_part('template_parts/front-page/newsSlider') ?>
    <?php get_template_part('template_parts/front-page/subscribeForm') ?>

    <!-- Зафиксированные элементы для связи и скролла справа -->

    <div class="float_box">
      <div class="float_block cf">
        <a href="/support/locate/" class="float_item">
          <div class="img"><img src="/wp-content/themes/pantum/assets/img/float_icon2.png"  decoding='async' width='30' height='60'></div>
          <div class="f-14 c-999 mt5">Сервис</div>
        </a>
        <a href="/appeal-to-pantum/" class="float_item">
          <div class="img"><img src="/wp-content/themes/pantum/assets/img/float_icon3.png" decoding='async' width='30' height='60'></div>
          <div class="f-14 c-999 mt5">Мы на<br>связи</div>
        </a>
      </div>
      <div class="gotop">
        <img src="/wp-content/themes/pantum/assets/img/arrow-to-top.svg" loading='lazy' decoding='async' width='24' height='24'>
      </div>
    </div>
</main>


<?php get_footer(); ?>