( function( $ ) {

	/**
	 * A function to move the hero image behind the header.
	 */
	function hero_image() {

		var body, header, header_height, hero, window_width;

		body = $( 'body' );
		header = $( '.site-header' );
		header_height = header.outerHeight();
		if ( $( '.site-top-content' ).length ) {
			header_height = header_height - $( '.site-top-content' ).outerHeight();
		}
		hero = $( '.hero' );
		window_width = $( window ).width();

		if ( body.hasClass( 'hero-image' ) && window_width >= 1230 ) {
			header.css( 'margin-bottom', - header_height );
			if ( hero.hasClass( 'with-featured-image' ) ) {
				hero.css( 'padding-top', 216 + header_height );
			} else {
				hero.css( 'padding-top', 144 + header_height );
			}
		} else if ( body.hasClass( 'hero-image' ) && window_width >= 1020 ) {
			header.css( 'margin-bottom', - header_height );
			if ( hero.hasClass( 'with-featured-image' ) ) {
				hero.css( 'padding-top', 144 + header_height );
			} else {
				hero.css( 'padding-top', 96 + header_height );
			}
		} else {
			header.css( 'margin-bottom', '' );
			hero.css( 'padding-top', '' );
		}

		header.fadeIn( 250 );

	}

	$( window ).load( hero_image ).resize( hero_image );

} )( jQuery );
