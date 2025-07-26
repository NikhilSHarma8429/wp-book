( function( blocks, editor, components, i18n, element ) {
    const el = element.createElement;
    const { __ } = i18n;

    blocks.registerBlockType( 'wp-book/book-block', {
        title: __( 'Book Block', 'wp-book' ),
        icon: 'book',
        category: 'widgets',
        attributes: {
            author: {
                type: 'string',
                default: ''
            }
        },

        edit: function( props ) {
            function updateAuthor( event ) {
                props.setAttributes( { author: event.target.value } );
            }

            return el(
                'div',
                { className: props.className },
                el( 'label', {}, 'Author Name:' ),
                el( 'input', {
                    type: 'text',
                    value: props.attributes.author,
                    onChange: updateAuthor,
                    placeholder: 'Enter author name (e.g., Nikhil)'
                } )
            );
        },

        save: function( props ) {
            return el(
                'div',
                {},
                '[book author="' + props.attributes.author + '"]'
            );
        }
    } );
} )(
    window.wp.blocks,
    window.wp.blockEditor || window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
);
