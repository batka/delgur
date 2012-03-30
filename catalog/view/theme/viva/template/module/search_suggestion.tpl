<style type="text/css">
	.ui-menu-item{
		text-align:left;
	}
</style>
<script type="text/javascript"><!--
//########################################################################
// Module: Search Autocomplete
//########################################################################
$(document).ready(function(){
	$( "#filter_keyword" ).autocomplete({
		source: function(request, response){
			$.ajax({
				url: "<?php echo $search_json;?>",
				dataType: "jsonp",
				data: {
					keyword: request.term,
					category_id: $("#filter_category_id").val()
				},
				success: function(data) {
					response( $.map( data.result, function(item){
						return {
							label: item.name,
							desc: item.price, 
							value: item.href
						}
					}));
				}
			});
		},
		focus: function(event, ui){
			return false;
		},
		select: function(event, ui){
			if(ui.item.value == ""){
				return false;
			}else{
				location.href=ui.item.value;
				return false;
			}
		}, 
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.label + "<br>" + item.desc + "</a>" )
			.appendTo( ul );
	};
})
//########################################################################
// Module: Search Autocomplete
//########################################################################
//--></script>