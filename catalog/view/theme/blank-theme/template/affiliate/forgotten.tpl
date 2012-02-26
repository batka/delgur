<?php echo $header; ?>
<?php echo $column_left; ?>
<?php echo $column_right; ?>
<section id="content">
	<?php echo $content_top; ?>
  	<section class="breadcrumb">
    	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
    		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    	<?php } ?>
  	</section>
  	<h1><?php echo $heading_title; ?></h1>
  	<?php if ($error_warning) { ?>
  		<div class="warning"><?php echo $error_warning; ?></div>
  	<?php } ?>
  	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">
    	<p><?php echo $text_email; ?></p>
    	<h2><?php echo $text_your_email; ?></h2>
    	<section class="content">
      		<table class="form">
        		<tr>
          			<td>
						<label for="email"><?php echo $entry_email; ?></label>
					</td>
          			<td><input type="text" name="email" value="" /></td>
        		</tr>
      		</table>
    	</section>
    	<div class="buttons">
      		<div class="left">
				<a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a>
			</div>
      		<div class="right">
				<a onclick="$('#forgotten').submit();" class="button"><?php echo $button_continue; ?></a>
			</div>
    	</div>
  	</form>
  	<?php echo $content_bottom; ?>
</section>
<?php echo $footer; ?>