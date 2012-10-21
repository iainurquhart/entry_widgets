$(function() {

		$.fn.updateWidgetIndexes = function(options){
		    $(this).find(".entry_widget").each(function(rowCount){
		        regex = /^(.*)\[(?:[0-9]+)\](.*)$/;
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
					if ($field.attr('class') == 'widget_shortcode') {
						shortcode = $field.val().split(':');
						if(shortcode[0] && shortcode[1]){
							$field.val( shortcode[0] + ':' + (rowCount+1) + '}' );
						}
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
			var field_name = $(this).data('field-name');

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