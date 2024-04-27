
									
<section class="inService_box">
    <div class="wrap1">
		<h2 class="f-48 tc grey">С заботой о клиентах</h2>
		<div class="inSer_con mt60 cf pr">
			<div class="inSer_tab pa">
				<a href="<?= get_field('support_up_link'); ?>" class="dpb">
					<div class="img">
							<img src="<?= get_field('support_up_icon')["url"];?>" loading='lazy' decoding='async' width='40' height='80'>
					</div>
					<div class="desc hid">
						<div class="txt">
							<div class="tit1 f-24 ellipsis grey"><?= get_field('support_up_title'); ?></div>
							<div class="tit2 f-16 ellipsis-2 c-64656a mt10"></div>
						</div>
						<div class="btn"></div>
					</div>
				</a>
				<a href="<?= get_field('support_down_link'); ?>" class="dpb">
					<div class="img"><img src="<?= get_field('support_down_icon')["url"]; ?>" loading='lazy' decoding='async' width='40' height='80'></div>
					<div class="desc hid">
						<div class="txt">
							<div class="tit1 f-24 ellipsis grey"><?= get_field('support_down_title'); ?></div>
							<div class="tit2 f-16 ellipsis-2 c-64656a mt10"></div>
						</div>
						<div class="btn"></div>
					</div>
				</a>
			</div>
			<div class="inSer_imgs">
				<a href="/support/download/driver/" class="tran_scale dpb pr inSer_imgs__link">
					<div class="img">
								<img src="<?= get_field('support_bg')["url"]; ?>" loading='lazy' decoding='async' width='347' height='222'>
						</div>
					<div class="txt pa">
						<h3 class="f-48 c-fff "><?= get_field('support_title'); ?></h3>
						<div class="f-20 c-fff op7 mt20"><?= get_field('support_text'); ?></div>
						<div class="btn_more f-20 c-fff mt30">Подробнее</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</section>