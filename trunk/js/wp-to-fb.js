jQuery( document ).ready( function( $ ) {
	
		//manual contents behaviour
		$( "#available, #selected" ).sortable( {
			revert : 100,
			opacity : 0.5,
			connectWith : ".drag_column",
			tolerance : "pointer"

		} ).disableSelection();
		
		//update hidden field with manual contents choosen
		$( "#selected" ).bind( "sortupdate", update_selected_contents_field_value );
		
		//show and hide automatic or manual zones for choosing contents
		$( "form input:radio[ name='wptofb_custom' ]" ).change( function(){
			
			if( $( this ).val() == "manual" ){
				$( "#automatic_fields" ).hide();
				$( "#custom_fields" ).show();
				$( "#manual_posts legend" ).css( "font-weight", "bold" );
				$( "#automatic_posts legend" ).css( "font-weight", "normal" );
				
				update_selected_contents_field_value();
				
			}else if( $( this ).val() == "automatic" ){
				$( "#automatic_fields" ).show();
				$( "#custom_fields" ).hide();
				$( "#automatic_posts legend" ).css( "font-weight", "bold" );
				$( "#manual_posts legend" ).css( "font-weight", "normal" );
				
				empty_hidden_field_ids();
				//automatic zone - uncheck all 
				$( 'ul#taxonomy_ul input:checkbox' ).attr( 'checked', false );
				
			}
		})
		
		//show or hide type of contents on manual contents zone
		$( "div#custom_fields :checkbox" ).change( function(){
			var val = $( this ).val();
			
			if( $( this ).is( ':checked' ) ){
				$( '#manual_posts li[ class*="'+val+'" ]' ).show();
			}else{
				$( '#manual_posts li[ class*="'+val+'" ]' ).hide();
			}
			
			update_selected_contents_field_value();
		});
		
		//show taxonomy associated with a type of content on automatic contents zone
		$( "form input:radio[ name='wptofb_types_of_content_automatic' ]").change( function(){
			var val = $( this ).val();
			
			$( 'ul#taxonomy_ul li[ class="li_'+val+'" ]' ).show();
			$( 'ul#taxonomy_ul li[ class!="li_'+val+'" ]' ).hide();
			$( 'ul#taxonomy_ul li[ class!="li_'+val+'" ] input:checkbox' ).attr( 'checked', false );
		
		})

		//these values are the individual ids of posts passed to mysql
		function update_selected_contents_field_value(){
			
			var ids = $( '#selected' ).find( 'li:visible' ).map(function () {
				var subs = this.id.substring(5);
				return subs; 
			  } ).get().join( ',' );
			$( '#wptofb_ids_conns' ).val( ids );	
		}
		
		//if not needed, empty values for ids of individual posts passed to mysql
		function empty_hidden_field_ids(){
			$( '#wptofb_ids_conns' ).val( '' );	
		}
		
		var id = 'wptofb_introtext';

		$('a.toggleVisual').click(
			
			function() {
				var id = $(this).attr("href").substring(1, $(this).attr("href").length);
				tinyMCE.execCommand('mceAddControl', false, id);
			}
		);

		$('a.toggleHTML').click(
			function() {
				var id = $(this).attr("href").substring(1, $(this).attr("href").length);
				tinyMCE.execCommand('mceRemoveControl', false, id);
			}
		);

});
