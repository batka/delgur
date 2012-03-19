<script>
  (function(){
    
    var body = $('body');
    body.on('mouseover', 'dl.cate-list-item', function(){
      $(this)
        .addClass('cate-current');
        //.siblings().removeClass('cate-current');
    });
    body.on('mouseout', 'dl.cate-list-item', function(){
      $(this).removeClass('cate-current');
    });

  })();
  
</script>
<?php /*<!-- Footer Social -->
<div id="footer-border"></div>
<div class="clear"></div>
<div id="footer-top-outside">
<div id="customHome" class="container_12"><div id="about_us_footer" class="grid_3">
<h2>О Магазин Тао</h2>
<p>Магазин Тао – это сервис услуг, который поможет Вам приобрести товары из самого большого интернет-магазина Китая – Таобао.</p>
<p>Taobao, Taobao.com — онлайновый интернет-магазин, ориентированный на потребителей в Китае.</p>
</div>
 <!--  TWITTER --> <div id="twitter_footer" class="grid_3">
<h2>Latest tweets</h2>
<ul id="twitter_update_list">
  <li><span></span></li>
  <li><span></span></li>
</ul>
</div>
 <div id="contact_footer" class="grid_3">
<h2>Контакты!</h2>
<ul>
<li>
  <!-- TELEPHONE 1 -->
  <ul id="tel" class="contact_column">
    <li id="footer_telephone"></li>


    <!-- TELEPHONE 2 -->
        <li id="footer_telephone2"></li>
      </ul>
  
  <!-- FAX  -->
    <ul id="fax" class="contact_column">
    <li id="footer_fax"></li>
  </ul>
  
  <!-- EMAIL 1 -->
    <ul id="mail" class="contact_column">
    <li id="footer_email">hello@MagazinTao.com</li>



    <!-- EMAIL 2 -->
        <li id="footer_email2">admin@MagazinTao.com</li>
      </ul>
  
  <!-- SKYPE -->
    <ul id="skype" class="contact_column">
    <li id="footer_skype"></li>
  </ul>
  </li>
</ul>
</div>
  <!--  FACEBOOK --> 
  <div id="facebook_footer" class="grid_3">
    <h2>Facebook</h2>


  </div>
 <!--  CUSTOM COLUMN -->  <!-- Categories --> </div>
</div>
*/?>

<div id="footer">
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
</div>

<div id="powered"><?php echo $powered; ?></div>

</div> <!-- END #Container -->
</body></html>