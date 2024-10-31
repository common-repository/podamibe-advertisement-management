(function() {

    tinymce.PluginManager.add('rashortcodes', function( editor )
    {
        var shortcodeValues = [];
        jQuery.each(shortcodes_button, function(i)
        {
            shortcodeValues.push({text: shortcodes_button[i], value:i});
        });

        editor.addButton('rashortcodes', {
            type: 'listbox',
            text: 'Shortcodes',
            onselect: function(e) {
                var v = e.target.settings.text;
                tinyMCE.activeEditor.selection.setContent('[' + v + '][/' + v + ']');
            },
            values: shortcodeValues
        });
    });
})(jQuery);