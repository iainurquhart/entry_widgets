<script>
	$(function() {

		$.fn.updateWidgetIndexes = function(options){
		    $(this).find(".entry_widget").each(function(rowCount){
		        regex = /^([a-z_]+)\[(?:[0-9]+)\](.*)$/;
		        $(this).attr('data-index', rowCount);
		        $(this).find('input, select, textarea, file').each(function(fieldCount){
		            $field = $(this);
		            if ($field.attr('name')) {
		                var fieldName = $field.attr('name').replace(regex, '$1[' + rowCount + ']$2');
		                $field.attr('name', fieldName);
		            }
		            if ($field.attr('class') == 'panel-count') {
		            	$field.val(rowCount);
		            }
		        });

		        $(this).find('.add-panel').each(function(panelCount){
		            $panel_button = $(this);

		            if ($field.attr('class') == 'panel-count') {
		            	$panel_button.attr('data-index', rowCount);
		            }
		        });
		    });
		};

		$("body").delegate(".add-widget-button", "click", function(e) {


			var area_id = $(this).parent().data('area-id');

			$('#widget-list-'+area_id).dialog({
			autoOpen: true,
			height: 200,
			width: 200,
			modal: true
			});

			e.preventDefault();

		});
		
		$("body").delegate("ul.entry_widget_list a", "click", function(e) {

			var widget_id = $(this).data('widget-id');
			var widget_area_id = $(this).data('area-id');
			var field_name = '<?=$field_name?>';

			$.getJSON(EE.BASE+"&C=addons_modules&M=show_module_cp&module=entry_widgets&method=ajax_add_instance&widget_id="+widget_id+"&field_name="+field_name+"&widget_area_id="+widget_area_id, {}, function(data) {
				
				console.log(data);
				$('#widget-area-'+widget_area_id).append( data.view );
				$('#widget-area-'+widget_area_id).updateWidgetIndexes();
				$(".ui-dialog-content").dialog("close");

			});
			e.preventDefault();
		});


		$("body").delegate("a.widget-delete", "click", function(e) {
    		
    		if(confirm('Are you sure you want to delete this Widget?')){
	    		$(this).closest('div.entry_widget').slideUp('slow', function() {
				    $(this).remove();
				    $('.entry_widget_areas').updateWidgetIndexes();
				});
			}else{
				return false;
			};
    		e.preventDefault();
    	});

    	$(".entry_widget_data").sortable({
			handle: 'table',
			start: function(e, ui){
		        ui.placeholder.height(ui.item.height());
		    },
			cursor: 'move',
			placeholder: "widget_placeholder",
			items: ".entry_widget",
			update: function(event, ui) { 
				$('.entry_widget_areas').updateWidgetIndexes(); 
			}
		});


	});
</script>

<style>
	#mainContent ul.entry_widget_areas,
	#mainContent ul.entry_widget_areas li {
		list-style-type: none;
		margin: 0;
		padding:0;
	}
	#mainContent ul.entry_widget_areas li.widget-area-wrapper {
		padding: 10px 15px;
		background: rgba(255,255,255, 0.8);
		margin: 10px 0 ;
		border-radius: 5px;
		border: 1px solid rgba(0,0,0,0.15);
	}
	#mainContent li.widget-area-wrapper h3 {
		margin: 0; 
		padding:0;
	}

	#mainContent li.widget-area-wrapper div.entry_widget_data ol, 
	#mainContent li.widget-area-wrapper div.entry_widget_data li {
		list-style-type: none;
	}
	#mainContent ul.entry_widget_areas tr.widget-options td {
		padding-top: 10px;
	}
	#mainContent ul.entry_widget_areas tr.widget-options li {
		padding-bottom: 10px;
	}
	#mainContent button.add-widget-button {
		color: #050505;
		padding: 5px 10px;
		margin: 10px 0;
		background: -moz-linear-gradient(
			top,
			#ffffff 0%,
			#ebebeb 50%,
			#dbdbdb 50%,
			#b5b5b5);
		background: -webkit-gradient(
			linear, left top, left bottom, 
			from(#ffffff),
			color-stop(0.50, #ebebeb),
			color-stop(0.50, #dbdbdb),
			to(#b5b5b5));
		-moz-border-radius: 10px;
		-webkit-border-radius: 10px;
		border-radius: 10px;
		border: 1px solid #949494;
		-moz-box-shadow:
			inset 0px 0px 2px rgba(255,255,255,1);
		-webkit-box-shadow:
			inset 0px 0px 2px rgba(255,255,255,1);
		box-shadow:
			inset 0px 0px 2px rgba(255,255,255,1);
		text-shadow:
			0px -1px 0px rgba(000,000,000,0.2),
			0px 1px 0px rgba(255,255,255,1);
	}
	.entry_widget {
		padding: 10px 0px 0px 0px;
		margin-bottom: 10px;
	}
	.entry_widget table {
		box-shadow: 3px 3px 8px rgba(0,0,0,0.3);
		border-radius: 3px;
	}
	.entry_widget_list * {
		outline:none;
	}
	#mainContent ul.entry_widget_areas th {
		font-size: 14px; line-height: 17px;
		height: 20px; vertical-align: middle;
		padding: 8px 10px;
	}
	#mainContent ul.entry_widget_areas a.widget-delete {
		float: right;
		text-decoration: none;
		background: #d91350 ;
		color: #fff;
		display: inline-block;
		width: 22px; height: 22px; text-align: center;
		line-height: 20px;
		margin: -2px 0 -5px 0;

	background: -moz-linear-gradient(
		top,
		#ff0000 0%,
		#400808);
	background: -webkit-gradient(
		linear, left top, left bottom, 
		from(#ff0000),
		to(#400808));
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	-moz-box-shadow:
		0px 1px 3px rgba(0,0,0,0.3),
		inset 0px 0px 1px rgba(0,0,0,0.3);
	-webkit-box-shadow:
		0px 1px 3px rgba(0,0,0,0.3),
		inset 0px 0px 1px rgba(0,0,0,0.3);
	box-shadow:
		0px 1px 3px rgba(0,0,0,0.3),
		inset 0px 0px 1px rgba(0,0,0,0.3);
	text-shadow:
		0px -1px 0px rgba(000,000,000,0.4),
		0px 1px 0px rgba(255,255,255,0.3);
	}
	#mainContent .widget_placeholder {
		background: rgba(0,0,0,0.3);
		box-shadow: 0 0 15px rgba(0,0,0,0.3);
		margin: 0px 0;
		padding: 10px;
	}
</style>



<ul class="entry_widget_areas">
<?php foreach($available_widget_areas as $widget_areas):?>
	<li data-area-id="<?= $widget_areas->id ?>" class="widget-area-wrapper">
		<h3><?= $widget_areas->title ?></h3>

		<button class="add-widget-button">Add a Widget</button>

		<ul class="entry_widget_list" id="widget-list-<?= $widget_areas->id ?>" style="display:none;">
			<li><h3>Choose a widget type</h3></li>
		<?php foreach($available_widgets as $widget):?>
			<li>
				<a href="#" data-widget-id="<?= $widget->id ?>" data-area-id="<?= $widget_areas->id ?>"><?= $widget->title ?></a>
			</li>
		<?php endforeach ?>
		</ul>

		<div class="entry_widget_data" id="widget-area-<?= $widget_areas->id ?>">

			<?php 
				if(isset($widgets[$widget_areas->slug]))
				{
					foreach( $widgets[$widget_areas->slug] as $widget)
					{
						echo $widget;
					}
				}
			?>
		</div>
	</li>
<?php endforeach ?>
</ul>






<!--
Array ( [0] => stdClass Object ( [id] => 1 [slug] => html [title] => HTML [description] => Create blocks of custom HTML. [author] => Phil Sturgeon [website] => http://philsturgeon.co.uk/ [version] => 1 [site_id] => 0 ) )
-->