var checkCPCSSGenerationCall;
var cpcsssGenerationPending = 0;
var rocketDeleteCPCSSbtn    = document.getElementById( 'rocket-delete-post-cpss' );
var rocketGenerateCPCSSbtn  = document.getElementById( 'rocket-generate-post-cpss' );
var rocketCPCSSGenerate     = document.querySelectorAll( '.cpcss_generate' );
var rocketCPCSSReGenerate   = document.querySelectorAll( '.cpcss_regenerate' );

rocketDeleteCPCSSbtn.addEventListener( 'click', function( e ) {
	e.preventDefault();
	deleteCPCSS();
});

rocketGenerateCPCSSbtn.addEventListener( 'click', function( e ) {
	e.preventDefault();
	checkCPCSSGeneration();
});

function checkCPCSSGeneration( timeout = null ) {
	var spinner              = rocketGenerateCPCSSbtn.querySelector( '.spinner' );
	spinner.style.display    = 'block';
	spinner.style.visibility = 'visible';

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if ( this.readyState == 4 && this.status == 200 ) {
			var cpcss_response = JSON.parse( this.responseText );
			if ( cpcss_response.data.status !== 200 ) {
				stopCPCSSGeneration( spinner );
				cpcssNotice( cpcss_response.message, 'error' );
				return;
			}

			if ( cpcss_response.data.status === 200 && cpcss_response.code !== 'cpcss_generation_pending') {
				stopCPCSSGeneration( spinner );
				cpcssNotice( cpcss_response.message, 'success' );
				// Revert view to Regenerate.
				rocketGenerateCPCSSbtn.querySelector( '.rocket-generate-post-cpss-btn-txt' ).innerHTML = cpcss_regenerate_btn;
				rocketDeleteCPCSSbtn.style.display                                                     = 'block';
				rocketCPCSSGenerate.forEach( function( item ) {
					item.style.display = 'none';
				});
				rocketCPCSSReGenerate.forEach( function( item ) {
					item.style.display = 'block';
				});
				return;
			}

			cpcsssGenerationPending++;

			if ( cpcsssGenerationPending > 10 ) {
				stopCPCSSGeneration( spinner );
				cpcsssGenerationPending = 0;
				checkCPCSSGeneration( true );
				return;
			}

			checkCPCSSGenerationCall = setTimeout(function () {
				checkCPCSSGeneration();
			}, 3000);
		}
	};

	xhttp.open( 'POST', cpcss_rest_url, true );
	xhttp.setRequestHeader( 'Content-Type', 'application/json;charset=UTF-8' );
	xhttp.setRequestHeader( 'X-WP-Nonce', cpcss_rest_nonce );
	xhttp.send( JSON.stringify( { timeout: timeout } ) );
}

function stopCPCSSGeneration( spinner ) {
	spinner.style.display = 'none';
	clearTimeout( checkCPCSSGenerationCall );
}

function deleteCPCSS() {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if ( this.readyState == 4 && this.status == 200 ) {
			var cpcss_response = JSON.parse( this.responseText );
			if ( cpcss_response.data.status !== 200 ) {
				cpcssNotice( cpcss_response.message, 'error' );
				return;
			}
			cpcssNotice( cpcss_response.message, 'success' );

			// Revert view to Generate.
			rocketGenerateCPCSSbtn.querySelector( '.rocket-generate-post-cpss-btn-txt' ).innerHTML = cpcss_generate_btn;
			rocketDeleteCPCSSbtn.style.display                                                     = 'none';
			rocketCPCSSReGenerate.forEach( function( item ) {
				item.style.display = 'none';
			});
			rocketCPCSSGenerate.forEach( function( item ) {
				item.style.display = 'block';
			});
		}
	};

	xhttp.open( 'DELETE', cpcss_rest_url, true );
	xhttp.setRequestHeader( 'Content-Type', 'application/json;charset=UTF-8' );
	xhttp.setRequestHeader( 'X-WP-Nonce', cpcss_rest_nonce );
	xhttp.send();
}

function cpcssNotice( msg, type ) {
	/* Add notice class */
	var cpcssNotice = document.getElementById( 'cpcss_response_notice' );
	cpcssNotice.innerHTML = '';
	cpcssNotice.classList.remove( 'notice', 'notice-error', 'notice-success');
	cpcssNotice.classList.add( 'notice', 'notice-' + type );

	/* create paragraph element to hold message */
	var p = document.createElement( 'p' );
	p.appendChild( document.createTextNode( msg ) );

	/* Add the whole message to notice div */
	cpcssNotice.appendChild( p );
}
