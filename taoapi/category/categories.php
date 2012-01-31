<style>

	a:link {
	 font-size: 9pt; COLOR: #333333; TEXT-DECORATION: none;
	}
	a:hover {
	 font-size: 9pt; COLOR: #FF5400; TEXT-DECORATION:underline;
	}
	.items_table {
		border-top:1px solid #ccc;
	}
	.items_table td {
		border-bottom:1px solid #ccc;
		padding:2px;
	}
	.items_table .sub_category {
		margin-left:10px;
		color:blue;
	}
	h3 { margin:0; padding:0;}
	/**
	 *------------------分页样式---------------------
	 */	
</style>
<h3><?=$parent_name ?></h3>
<div style="display:none;"><iframe src="" name="download_frame" id="download_frame"></iframe></div>
<form action="/taoapi/category/download.php" method="post" enctype="multipart/form-data" target="download_frame">
<input type="submit" value="提交">
<input type="hidden" name="parent_cid" value="<?=$parent_cid ?>" />
<table border="0" width="100%" class="items_table" cellpadding="0" cellspacing="0">
	<?php
	if (!empty($Categories))
	{
		foreach ($Categories as $key => $val)
		{ 
			$link = $this->url->link('taoapi/down_category', "parent_cid={$val['cid']}&parent_name=".urldecode($val['name']).'&token=' . $this->session->data['token'], 'SSL');
	?>
	<tr>
		<td>
			<input type="checkbox" name="cid[]" value="<?=$val['cid'].':'.($val['is_parent'] == 'true' ? 1 : 0).':'.$val['name'] ?>" /><?=$val['name'] ?> 
			<?php if (strval($val['is_parent']) == 'true'): ?>
				<a href="<?=$link; ?>" class="sub_category">+子分类</a>
			<?php endif; ?>
		</td>
	</tr>
	<?php
		}
	}
	else
	{
		echo '<tr><td colspan=15 class="sub_msg">没有找到分类</td></tr>';
	}
	?>
</table>
</form>