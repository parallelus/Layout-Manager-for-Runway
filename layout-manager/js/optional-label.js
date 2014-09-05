jQuery( document ).ready(function( $ ) {
  	$('#layout').change(function(e){	
		$('#optional_label_area').remove();
		$('.metaField_field_optional_label').remove();

		if($(this).find(":selected").val() == 0)
			return true;

  		$('.metaField_field_layout').after($( '<div class="spinner" style="display: block;"></div>' ));
  		$('.spinner').addClass('optional-label-spinner');

  		var alias = $(this).val();
		var post_ID = ($('#post_ID').length !== 0) ? $('#post_ID').val() : -1;
		var postType = ($('#post_type').length !== 0) ? $('#post_type').val() : -1;
                
		$.ajax({
			url: ajaxurl,
			data: {
				action:'get_optional_labels',
				alias: alias,
				post_ID: post_ID,
				postType: postType
			}

		}).done(function(response){

			var data = $.parseJSON($.trim(response));
			// console.log(response);

			$('.metaField_field_layout').after($( '<div id="optional_label_area"></div>' ));
			$('#optional_label_area').hide();
			for(var index in data) {
				$('#optional_label_area').append($( '<div class="metaField_field_wrapper metaField_field_optional_label metaField_field_'+data[index].content_type+'"><p><label for="'+data[index].alias+'">'+data[index].label+'</label></p><select class="metaField_select" id="'+index+'"></select></div>' ));
				var select = $('#'+index);
				var option = $('<option>', {
					value: 0,
					text: '- Select -'
					});
				select.append(option);

				$.ajax({
					url: ajaxurl,
					async: false,
					data: {
						action:'get_content_elements',
						source: data[index].content_type
					}
				}).done(function(response){

					var opt = $.parseJSON($.trim(response));
					for(var i in opt) {
						option = $('<option>', {
							value: opt[i].alias,
							text: opt[i].title
						});			
						select.append(option);
					}
				});
				select.val(data[index].content_source);
				select.data('index', index);
			}
			$('.spinner').removeClass('optional-label-spinner');
			$('.spinner').hide();
			$('#optional_label_area').show();
		});
	});

	$('#publish').one('click', function( e ) {

		e.preventDefault();
		var alias = $('#layout').val();
		var source;
		var post_ID = ($('#post_ID').length !== 0) ? $('#post_ID').val() : -1;
		$('#publishing-action .spinner').show();
		
		if ($('.metaField_field_optional_label select').length == 0) {
			$('#publish').trigger( "click" );
		}
		else {
			$('.metaField_field_optional_label select').each(function(e){	
				source = $(this).val();
				$.ajax({
					url: ajaxurl,
					async: false,
					data: {
						action:'save_optional_labels',
						alias: alias,
						source: source,
						post_ID: post_ID,
						index: $(this).data('index')
					}
				}).done(function(response){	
					$('#publish').trigger( "click" );
				});
			});
		}
	}); 

});
