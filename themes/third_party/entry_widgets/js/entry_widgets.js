$(function() {

		$.fn.updateWidgetIndexes = function(options){
		    $(this).find(".entry_widget").each(function(rowCount){
		        regex = /^(.*?)\[(?:[0-9]+)\](.*)$/;
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

		$.fn.characterLimiter = function (limit) {
		    return this.filter("textarea, input:text").each(function () {
		        var $this = $(this),
		        checkCharacters = function (event) {

		            if ($this.val().length > limit) {

		                // Trim the string as paste would allow you to make it 
		                // more than the limit.
		                $this.val($this.val().substring(0, limit))
		                // Cancel the original event
		                event.preventDefault();
		                event.stopPropagation();

		                $this.css({'background-color': '#ffcccc' });

		            }
		            else
		            {
		            	$this.css({'background-color': '#fff' });
		            }
		        };

		        $this.keyup(function (event) {

		            // Keys "enumeration"
		            var keys = {
		                BACKSPACE: 8,
		                TAB: 9,
		                LEFT: 37,
		                UP: 38,
		                RIGHT: 39,
		                DOWN: 40
		            };

		            // which normalizes keycode and charcode.
		            switch (event.which) {

		                case keys.UP:
		                case keys.DOWN:
		                case keys.LEFT:
		                case keys.RIGHT:
		                case keys.TAB:
		                    break;
		                default:
		                    checkCharacters(event);
		                    break;
		            }
		        });

		        // Handle cut/paste.
		        $this.bind("paste cut", function (event) {
		            // Delay so that paste value is captured.
		            setTimeout(function () { checkCharacters(event); event = null; }, 150);
		        });
		    });
		};

		$("body").delegate(".add-widget-button", "click", function(e) {

			var widget_select = $(this).prev('select');

			var field_name = $('option:selected', widget_select).data('field-name');
			var widget_id  = $('option:selected', widget_select).data('widget-id');
			var widget_area_id = $('option:selected', widget_select).data('area-id');

			// fetch our widget via json
			$.getJSON(EE.BASE+"&C=addons_modules&M=show_module_cp&module=entry_widgets&method=ajax_add_instance&widget_id="+widget_id+"&field_name="+field_name+"&widget_area_id="+widget_area_id, {}, function(data) {

				console.log(data);
				$('#widget-area-'+widget_area_id).append( data.view );
				$('#widget-area-'+widget_area_id).updateWidgetIndexes();

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