(function() {
    tinymce.PluginManager.add('easyimagecollage', function( editor, url ) {
        function replaceShortcodes( content ) {
            return content.replace( /\[easy-image-collage([^\]]*)\]/g, function( match ) {
                return html( match );
            });
        }

        function html( data ) {
            var id = data.match(/id="?'?(\d+)/i);
            data = window.encodeURIComponent( data );
            return '<img src="' + eic_admin.shortcode_image + '" class="mceItem eic-shortcode" ' +
                'data-eic-grid="' + id[1] + '" data-eic-shortcode="' + data + '" data-mce-resize="false" data-mce-placeholder="1" />';
        }

        function restoreShortcodes( content ) {
            function getAttr( str, name ) {
                name = new RegExp( name + '=\"([^\"]+)\"' ).exec( str );
                return name ? window.decodeURIComponent( name[1] ) : '';
            }

            return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
                var data = getAttr( image, 'data-eic-shortcode' );

                if ( data ) {
                    return '<p>' + data + '</p>';
                }

                return match;
            });
        }

        editor.on( 'mouseup', function( event ) {
            var dom = editor.dom,
                node = event.target;

            if ( node.nodeName === 'IMG' && dom.getAttrib( node, 'data-eic-shortcode' ) ) {
                // Don't trigger on right-click
                if ( event.button !== 2 ) {
                    var id = dom.getAttrib( node, 'data-eic-grid' );
                    EasyImageCollage.btnEditGrid(id);
                }
            }
        });

        editor.on( 'BeforeSetContent', function( event ) {
            event.content = replaceShortcodes( event.content );
        });

        editor.on( 'PostProcess', function( event ) {
            if ( event.get ) {
                event.content = restoreShortcodes( event.content );
            }
        });
    });
})();