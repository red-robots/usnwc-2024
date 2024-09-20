(function() {  
  tinymce.PluginManager.add( 'BUTTON1', function( editor, url ) {
      //console.log(url);
      var parts = url.split('assets');
      var themeURL = parts[0];
      
      // Add Button to Visual Editor Toolbar
      editor.addButton('edbutton1', {
          title: 'Button Red',
          cmd: 'edbutton1',
          image: themeURL + 'images/button-red.png',
      });

      // Add Command when Button Clicked
      editor.addCommand('edbutton1', function() {
        var selected_text = editor.selection.getContent();
        if ( selected_text.length === 0 ) {
            alert( 'Please select some text.' );
            return;
        }
        var open_column = '<span class="custom-button-element red"><a data-mce-href="#" href="#"  data-mce-selected="inline-boundary" class="button-element button">';
        var close_column = '</a></span>';
        var return_text = '';
        return_text = open_column + selected_text + close_column;
        editor.execCommand('mceReplaceContent', false, return_text);
        return;
      });
  });


  tinymce.on('AddEditor', function (e) {
    console.log(e.editor.startContent);
    if( document.querySelector('.mceContentBody h2') ) {
      console.log('has h2');
    }
  });


  tinymce.PluginManager.add( 'BUTTON2', function( editor, url ) {
      var parts = url.split('assets');
      var themeURL = parts[0];
      
      // Add Button to Visual Editor Toolbar
      editor.addButton('edbutton2', {
          title: 'Button Pill Shape',
          cmd: 'edbutton2',
          image: themeURL + 'images/button-gray.png',
      });

      // Add Command when Button Clicked
      editor.addCommand('edbutton2', function() {
        var selected_text = editor.selection.getContent();
        if ( selected_text.length === 0 ) {
            alert( 'Please select some text.' );
            return;
        }
        var open_column = '<span class="custom-button-element"><a data-mce-href="#" href="#"  data-mce-selected="inline-boundary" class="button-element button button-gray">';
        var close_column = '</a></span>';
        var return_text = '';
        return_text = open_column + selected_text + close_column;
        editor.execCommand('mceReplaceContent', false, return_text);
        return;
      });
  });

  // if( $('.page-template-page-flexible-content-new.mce-content-body h2').length ) {

  // }



  

})();