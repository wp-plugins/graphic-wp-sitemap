// closure to avoid namespace collision
(function(){
	// creates the plugin
	tinymce.create('tinymce.plugins.mydownloadsbox', {
		// creates control instances based on the control's id.
		// our button's id is "mydownloadsbox_button"
		createControl : function(id, controlManager) {
			if (id == 'mydownloadsbox_button') {
				// creates the button
				var button = controlManager.createButton('mydownloadsbox_button', {
					title : 'Downloadsbox Shortcode', // title of the button
					image : '/wp-content/plugins/downloads-box/filetype_icons/downloadsbox_icon.png',  // path to the button's image
					onclick : function() {
						// triggers the thickbox
						var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 700 < width ) ? 700 : width;
						W = W - 80;
						H = H - 84;
						tb_show( 'My Downloadsbox Shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=mydownloadsbox-form' );
					}
				});
				return button;
			}
			return null;
		}
	});
	
	// registers the plugin. DON'T MISS THIS STEP!!!
	tinymce.PluginManager.add('mydownloadsbox', tinymce.plugins.mydownloadsbox);
	
	// executes this when the DOM is ready
	jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		// you should achieve this using AJAX instead of direct html code like this
		var form = jQuery('<div id="mydownloadsbox-form"><table id="mydownloadsbox-table" class="form-table">\
			<tr>\
				<th><label for="mydownloadsbox-title"><strong>Title:</strong> to go after &quot;DOWNLOAD&quot;</label></th>\
				<td><input type="text" id="mydownloadsbox-title" name="title" value="the files here" /><br />\
				<small>Give a title for this downloads box</small></td>\
			</tr>\
				<tr>\
				<th><label for="mydownloadsbox-nocredit">Remove love link</label></th>\
				<td><input type="checkbox" id="mydownloadsbox-nocredit" value="yes" name="nocredit" />\
			<small>Leave un-checked to support our plugin</small></td>\
			</tr>\
	</table>\
		<p class="submit">\
			<input type="button" id="mydownloadsbox-submit" class="button-primary" value="Create Downloadsbox" name="submit" />\
		</p>\
		</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#mydownloadsbox-submit').click(function(){
	
			var shortcode = '[downloads_box title="' + table.find('#mydownloadsbox-title').val() + '"';
			if(table.find('#mydownloadsbox-nocredit:checked').val() ) {
				shortcode += ' nocredit="yes"';
			}
				
			var selectionx = tinyMCE.activeEditor.selection.getContent({format : 'text'});
			
			if ( selectionx == '') {
			shortcode += ']<br/><a href="your-link-here">Edit_this_link.txt</a><br/>';
			} else {
			shortcode += ']<br/>' + selectionx + '<br/>';
			}
			shortcode += '[/downloads_box]';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})()