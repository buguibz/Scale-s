/**
 * search.js
 *
 * Handles toggling the search form.
 */
( function( $ ) {

	$( '.search-toggle' ).click( function() {
		$( 'html, body' ).scrollTop( 0 );
		$( this ).toggleClass( 'open' );
		$( '.search-wrapper' ).toggle();
		$( '.menu-toggle' ).removeClass( 'open' );
		$( '.navigation-wrapper' ).hide();
	} );

} )( jQuery );
