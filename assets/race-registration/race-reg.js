/**
 * Race registration experience (see inc/race-registration.php).
 *
 * Walks a multi-page Gravity Form one screen ("frame") at a time. The form
 * itself is untouched: every field stays inside the <form>, GF's own Next /
 * Previous / Submit buttons do the page changes (so server-side validation,
 * conditional logic, pricing, coupons and Stripe all behave as usual), and the
 * custom controls below just drive the real inputs.
 *
 * Fields that aren't on the current frame are "parked" (moved off-screen and
 * made inert) instead of display:none, because gformIsHidden() treats a
 * display:none product field as not selected and drops it from the total.
 *
 * Config comes from window.RWRegx (inc/race-registration/form-{ID}.php).
 *
 * Forms that collect people through a GP Nested Forms field (config 'nested')
 * get a roster screen instead of GPNF's table, and GPNF's modal becomes a
 * full-screen, one-question-at-a-time flow of the child form. GPNF still opens,
 * submits, edits and deletes the child entries; we only drive its buttons.
 */
( function ( $ ) {
	'use strict';

	var C = window.RWRegx;
	if ( ! C || ! C.formId ) {
		return;
	}

	var FID = C.formId,
		root = document.getElementById( 'rx' ),
		KEY = 'rx-' + FID + '-',
		reduceMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( ! root ) {
		return;
	}

	var el = {
		welcome: document.getElementById( 'rx-welcome' ),
		start: document.getElementById( 'rx-start' ),
		frame: document.getElementById( 'rx-frame' ),
		inner: root.querySelector( '.rx-frame-inner' ),
		art: root.querySelector( '.rx-art img' ),
		head: document.getElementById( 'rx-head' ),
		kicker: root.querySelector( '.rx-head .rx-kicker' ),
		title: root.querySelector( '.rx-head .rx-h2' ),
		lede: root.querySelector( '.rx-head .rx-lede' ),
		widget: document.getElementById( 'rx-widget' ),
		tip: document.getElementById( 'rx-tip' ),
		tipBody: root.querySelector( '#rx-tip .rx-tip-body' ),
		error: document.getElementById( 'rx-error' ),
		back: document.getElementById( 'rx-back' ),
		next: document.getElementById( 'rx-next' ),
		nextLabel: root.querySelector( '#rx-next .rx-next-label' ),
		done: document.getElementById( 'rx-done' ),
		doneMsg: document.getElementById( 'rx-done-message' ),
		progress: document.getElementById( 'rx-progress' ),
		progressFill: root.querySelector( '.rx-progress-fill' ),
		progressPct: root.querySelector( '.rx-progress-pct' ),
		total: document.getElementById( 'rx-total' ),
		totalValue: root.querySelector( '#rx-total .rx-total-value' )
	};

	var form, page = 1, lastPage = 1, pageEl, frames = [], idx = -1, busy = false, total = 0, initialized = false;

	// ---------- small helpers ----------

	var store = {
		get: function ( k ) { try { return window.sessionStorage.getItem( KEY + k ); } catch ( e ) { return null; } },
		set: function ( k, v ) { try { window.sessionStorage.setItem( KEY + k, v ); } catch ( e ) {} },
		del: function ( k ) { try { window.sessionStorage.removeItem( KEY + k ); } catch ( e ) {} }
	};

	function fieldEl( id, fid ) {
		return document.getElementById( 'field_' + ( fid || FID ) + '_' + id );
	}

	function fieldId( node ) {
		var m = node && node.id ? node.id.match( /^field_\d+_(\d+)$/ ) : null;
		return m ? parseInt( m[1], 10 ) : null;
	}

	function fieldType( node ) {
		var m = node.className.match( /gfield--type-([a-z_]+)/ );
		return m ? m[1] : '';
	}

	// Hidden by GF conditional logic (or by gf-zero-total.js), as opposed to parked by us.
	function isShown( node ) {
		return !! node && node.style.display !== 'none' && node.getAttribute( 'data-conditional-logic' ) !== 'hidden';
	}

	function toNumber( v ) {
		if ( typeof v === 'number' ) {
			return v;
		}
		var n = typeof window.gformToNumber === 'function' ? window.gformToNumber( v ) : false;
		if ( n === false || isNaN( n ) ) {
			n = parseFloat( String( v || '' ).replace( /[^0-9.\-]/g, '' ) );
		}
		return isNaN( n ) ? 0 : n;
	}

	function money( n ) {
		if ( typeof window.gformFormatMoney === 'function' ) {
			return window.gformFormatMoney( n, true );
		}
		return '$' + ( Math.round( n * 100 ) / 100 ).toFixed( 2 );
	}

	function niceMoney( n ) {
		return money( n ).replace( /\.00$/, '' );
	}

	function esc( s ) {
		return String( s == null ? '' : s ).replace( /[&<>"']/g, function ( c ) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ];
		} );
	}

	function change( input ) {
		// GF listens through jQuery; also fire a native event for anything else.
		$( input ).trigger( 'change' );
		input.dispatchEvent( new Event( 'input', { bubbles: true } ) );
		// jQuery's change doesn't reach native listeners, so recount here: an answer can add or
		// remove screens (party size, a branch).
		if ( idx >= 0 && root.dataset.view === 'frames' ) {
			updateProgress();
		}
	}

	function recalc() {
		if ( typeof window.gformCalculateTotalPrice === 'function' ) {
			window.gformCalculateTotalPrice( FID );
		}
	}

	// A frame with 'needs' only counts when that field's value is high enough (e.g. runner 3 of a party of 2).
	function counts( cfg ) {
		if ( ! cfg.needs ) {
			return true;
		}
		var n = fieldEl( cfg.needs.field ),
			input = n ? n.querySelector( 'select, input:checked, input[type="number"], input[type="text"]' ) : null;
		return ( input ? toNumber( input.value ) : 0 ) >= cfg.needs.min;
	}

	// {name} in a title or lede becomes the first name typed into the frame's nameField.
	function fill( text, cfg ) {
		if ( ! text || text.indexOf( '{name}' ) === -1 ) {
			return text || '';
		}
		var n = cfg.nameField ? fieldEl( cfg.nameField, cfg.fid ) : null,
			first = n ? n.querySelector( 'input[type="text"]' ) : null,
			name = first ? first.value.trim() : '';
		return text.replace( /\{name\}/g, name || cfg.nameFallback || 'this runner' );
	}

	function frameAt( offset ) {
		return stepFrom( frames, idx, offset );
	}

	function stepFrom( list, from, offset ) {
		for ( var i = from + offset; i >= 0 && i < list.length; i += offset ) {
			if ( frameVisible( list[ i ] ) ) {
				return i;
			}
		}
		return -1;
	}

	function frameVisible( f ) {
		return f.fields.some( function ( n ) {
			var t = fieldType( n );
			return t !== 'html' && t !== 'section' && t !== 'total' && isShown( n );
		} );
	}

	// ---------- building frames for the current GF page ----------

	function findPage() {
		var pages = form.querySelectorAll( '.gform_page' );
		lastPage = pages.length || 1;
		if ( ! pages.length ) {
			pageEl = form;
			page = 1;
			return;
		}
		for ( var i = 0; i < pages.length; i++ ) {
			if ( pages[ i ].style.display !== 'none' ) {
				pageEl = pages[ i ];
				var m = pages[ i ].id.match( /_(\d+)$/ );
				page = m ? parseInt( m[1], 10 ) : i + 1;
				return;
			}
		}
		pageEl = pages[0];
		page = 1;
	}

	function buildFrames() {
		frames = collectFrames( pageEl, ( C.frames || [] ).filter( function ( cfg ) {
			return ( cfg.page || 1 ) === page;
		} ), FID );
	}

	// Turn a container's GF fields into frames: configured ones first, then anything left over.
	function collectFrames( container, cfgs, fid ) {
		var nodes = Array.prototype.slice.call( container.querySelectorAll( '.gfield' ) ).filter( function ( n ) {
			return fieldId( n ) !== null;
		} );
		var claimed = {}, frames = [];

		cfgs.forEach( function ( cfg ) {
			var fields = [];
			( cfg.fields || [] ).forEach( function ( id ) {
				var n = fieldEl( id, fid );
				if ( n && container.contains( n ) ) {
					fields.push( n );
					claimed[ id ] = true;
				}
			} );
			if ( fields.length ) {
				frames.push( { cfg: cfg, fields: fields, primary: fields[0] } ); // primary: the first field the config lists, before DOM sorting
			}
		} );

		// Anything the config doesn't mention still gets shown somewhere.
		nodes.forEach( function ( n ) {
			var id = fieldId( n ), t = fieldType( n );
			// gform_hidden: hidden products (a flat registration fee) have nothing to ask but still count toward the total.
			if ( claimed[ id ] || t === 'page' || t === 'hidden' || n.classList.contains( 'gfield_visibility_hidden' ) || n.classList.contains( 'gform_hidden' ) ) {
				return;
			}
			if ( t === 'section' || t === 'total' ) {
				return;
			}
			if ( t === 'html' && frames.length ) {
				frames[ frames.length - 1 ].fields.push( n );
				return;
			}
			var label = n.querySelector( '.gfield_label' );
			frames.push( {
				cfg: { title: label ? label.textContent.replace( /\*\s*$/, '' ).trim() : '', solo: true, auto: true },
				fields: [ n ]
			} );
		} );

		// Keep DOM order inside each frame so labels and links stay next to their fields.
		frames.forEach( function ( f ) {
			f.fields.sort( function ( a, b ) {
				return a.compareDocumentPosition( b ) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1;
			} );
		} );

		nodes.forEach( function ( n ) {
			n.classList.add( 'rx-parked' );
			n.setAttribute( 'inert', '' );
		} );
		return frames;
	}

	// ---------- custom controls ----------

	var widgets = {

		// Big clickable cards for a select (e.g. division product).
		cards: function ( f ) {
			var field = f.primary || f.fields[0], select = field.querySelector( 'select' ),
				radios = select ? [] : Array.prototype.slice.call( field.querySelectorAll( '.gfield_radio input[type="radio"]' ) );
			if ( ! select && ! radios.length ) {
				return;
			}
			var options = select ? Array.prototype.map.call( select.options, function ( opt ) {
				return { value: opt.value, text: opt.text, disabled: opt.disabled };
			} ) : radios.map( function ( r ) {
				var l = field.querySelector( 'label[for="' + r.id + '"]' );
				return { value: r.value, text: l ? l.textContent.trim() : r.value, disabled: r.disabled, input: r };
			} );
			var wrap = document.createElement( 'div' );
			wrap.className = 'rx-cards';
			wrap.setAttribute( 'role', 'radiogroup' );
			options.forEach( function ( opt ) {
				if ( ! opt.value ) {
					return;
				}
				var parts = opt.value.split( '|' ),
					price = parts.length > 1 ? toNumber( parts[1] ) : null,
					// "Half Marathon - $75" reads better as a title plus the price line.
					title = price !== null ? productName( opt.text ) : opt.text,
					desc = ( f.cfg.cards || {} )[ title ] || ( f.cfg.cards || {} )[ opt.text ] || '',
					b = document.createElement( 'button' );
				b.type = 'button';
				b.className = 'rx-card';
				b.setAttribute( 'role', 'radio' );
				b.dataset.value = opt.value;
				b.disabled = opt.disabled;
				b.innerHTML = '<span class="rx-card-check" aria-hidden="true"></span>' +
					'<span class="rx-card-title">' + esc( title ) + '</span>' +
					( desc ? '<span class="rx-card-desc">' + esc( desc ) + '</span>' : '' ) +
					( price !== null ? '<span class="rx-card-price">' + esc( niceMoney( price ) ) + '</span>' : '' );
				b.addEventListener( 'click', function () {
					if ( select ) {
						select.value = opt.value;
						change( select );
					} else if ( ! opt.input.checked ) {
						opt.input.click(); // GF recalculates the total from the click
					}
					sync();
					clearFieldError( field );
				} );
				wrap.appendChild( b );
			} );
			function current() {
				if ( select ) {
					return select.value;
				}
				var on = radios.filter( function ( r ) { return r.checked; } )[0];
				return on ? on.value : null;
			}
			function sync() {
				var value = current();
				Array.prototype.forEach.call( wrap.children, function ( b ) {
					var on = b.dataset.value === value;
					b.classList.toggle( 'is-selected', on );
					b.setAttribute( 'aria-checked', on ? 'true' : 'false' );
				} );
			}
			( select ? [ select ] : radios ).forEach( function ( i ) {
				i.addEventListener( 'change', sync );
			} );
			sync();
			field.classList.add( 'rx-has-widget' );
			field.appendChild( wrap );
		},

		// Day-grouped time chips for a select whose options read "Day at Time".
		slots: function ( f ) {
			var field = f.primary || f.fields[0], select = field.querySelector( 'select' );
			if ( ! select ) {
				return;
			}
			var wrap = document.createElement( 'div' ), groups = [], byDay = {};
			wrap.className = 'rx-slots';
			Array.prototype.forEach.call( select.options, function ( opt ) {
				if ( ! opt.value ) {
					return;
				}
				var m = opt.text.match( /^(.*?)\s+at\s+(.+)$/i ),
					day = m ? m[1] : '',
					time = ( f.cfg.labels || {} )[ opt.text ] || ( m ? m[2] : opt.text );
				if ( ! byDay[ day ] ) {
					byDay[ day ] = [];
					groups.push( day );
				}
				byDay[ day ].push( { opt: opt, time: time } );
			} );
			groups.forEach( function ( day ) {
				var g = document.createElement( 'div' );
				g.className = 'rx-slot-day';
				g.innerHTML = day ? '<div class="rx-slot-day-title">' + esc( day ) + '</div>' : '';
				var row = document.createElement( 'div' );
				row.className = 'rx-slot-row';
				row.setAttribute( 'role', 'radiogroup' );
				if ( day ) {
					row.setAttribute( 'aria-label', day );
				}
				byDay[ day ].forEach( function ( s ) {
					var b = document.createElement( 'button' );
					b.type = 'button';
					b.className = 'rx-chip';
					b.setAttribute( 'role', 'radio' );
					b.dataset.value = s.opt.value;
					b.disabled = s.opt.disabled;
					b.innerHTML = esc( s.time.replace( /(\d)\s*(am|pm)$/i, '$1 $2' ) ) + ( s.opt.disabled ? ' <small>Full</small>' : '' );
					b.addEventListener( 'click', function () {
						select.value = s.opt.value;
						change( select );
						sync();
						clearFieldError( field );
					} );
					row.appendChild( b );
				} );
				g.appendChild( row );
				wrap.appendChild( g );
			} );
			function sync() {
				wrap.querySelectorAll( '.rx-chip' ).forEach( function ( b ) {
					var on = b.dataset.value === select.value;
					b.classList.toggle( 'is-selected', on );
					b.setAttribute( 'aria-checked', on ? 'true' : 'false' );
				} );
			}
			select.addEventListener( 'change', sync );
			sync();
			field.classList.add( 'rx-has-widget' );
			field.appendChild( wrap );
		},

		// Yes/No toggle + a quantity stepper per size, driving the size checkboxes and quantity products.
		shirts: function ( f ) {
			var s = f.cfg.shirts || {}, toggle = fieldEl( s.toggle ), sizes = fieldEl( s.sizes );
			if ( ! toggle || ! sizes || ! s.map ) {
				return;
			}
			sizes.classList.add( 'rx-controlled' );
			Object.keys( s.map ).forEach( function ( size ) {
				var p = fieldEl( s.map[ size ] );
				if ( p ) {
					p.classList.add( 'rx-controlled' );
				}
			} );

			var wrap = document.createElement( 'div' );
			wrap.className = 'rx-steppers';
			var rows = Object.keys( s.map ).map( function ( size ) {
				var product = fieldEl( s.map[ size ] ),
					qty = product ? product.querySelector( 'input.ginput_quantity, input[type="number"]' ) : null,
					base = product ? product.querySelector( 'input[id^="ginput_base_price_"]' ) : null,
					box = sizes.querySelector( 'input[type="checkbox"][value="' + size.replace( /"/g, '\\"' ) + '"]' );
				if ( ! qty || ! box ) {
					return null;
				}
				var row = document.createElement( 'div' );
				row.className = 'rx-stepper';
				row.innerHTML = '<div class="rx-stepper-label"><strong>' + esc( size ) + '</strong>' +
					( base ? '<span>' + esc( money( toNumber( base.value ) ) ) + ' each, tax incl.</span>' : '' ) + '</div>' +
					'<div class="rx-stepper-ctl">' +
					'<button type="button" class="rx-step" data-step="-1" aria-label="One fewer ' + esc( size ) + '">&minus;</button>' +
					'<output class="rx-step-val" aria-live="polite">0</output>' +
					'<button type="button" class="rx-step" data-step="1" aria-label="One more ' + esc( size ) + '">+</button>' +
					'</div>';
				var out = row.querySelector( 'output' );
				function current() {
					return box.checked ? Math.max( 0, parseInt( qty.value, 10 ) || 0 ) : 0;
				}
				function set( n ) {
					n = Math.max( 0, Math.min( 50, n ) );
					if ( n > 0 && ! box.checked ) {
						box.click(); // runs GF conditional logic, which reveals the quantity product
					} else if ( n === 0 && box.checked ) {
						box.click();
					}
					qty.value = n > 0 ? n : '';
					change( qty );
					$( qty ).trigger( 'keyup' );
					recalc();
					render();
				}
				function render() {
					var n = current();
					out.textContent = n;
					row.classList.toggle( 'has-qty', n > 0 );
					row.querySelector( '[data-step="-1"]' ).disabled = n === 0;
				}
				row.addEventListener( 'click', function ( e ) {
					var b = e.target.closest( '.rx-step' );
					if ( b ) {
						set( current() + parseInt( b.dataset.step, 10 ) );
						clearFrameError();
					}
				} );
				render();
				wrap.appendChild( row );
				return { set: set, current: current, render: render };
			} ).filter( Boolean );

			f.shirtCount = function () {
				return rows.reduce( function ( sum, r ) { return sum + r.current(); }, 0 );
			};
			f.wantsShirts = function () {
				var yes = toggle.querySelector( 'input[type="radio"]:checked' );
				return !! yes && /^y/i.test( yes.value );
			};

			function syncOpen() {
				var open = f.wantsShirts();
				wrap.classList.toggle( 'is-open', open );
				if ( ! open ) {
					rows.forEach( function ( r ) {
						if ( r.current() ) {
							r.set( 0 );
						}
					} );
				}
			}
			toggle.addEventListener( 'change', function () {
				syncOpen();
				clearFrameError();
			} );
			toggle.insertAdjacentElement( 'afterend', wrap );
			// The stepper block lives between fields, so it has to follow the frame like one.
			f.extra = wrap;
			syncOpen();
		},

		// Order recap on the payment screen.
		summary: function ( f ) {
			f.onEnter = function () {
				el.widget.innerHTML = summaryHtml( f.cfg );
			};
		},

		// The people added through a GP Nested Forms field, as cards with Edit / Remove, plus an Add button.
		roster: function ( f ) {
			var field = N ? fieldEl( N.field ) : null;
			if ( ! field ) {
				return;
			}
			field.classList.add( 'rx-has-widget', 'rx-nested' );
			( N.controlled || [] ).forEach( function ( id ) {
				var n = fieldEl( id );
				if ( n ) {
					n.classList.add( 'rx-controlled' );
				}
			} );

			var wrap = document.createElement( 'div' ), confirming = null;
			wrap.className = 'rx-roster';
			wrap.setAttribute( 'aria-live', 'polite' );
			field.appendChild( wrap );

			function render() {
				var list = nestedEntries(), inst = nestedInst(),
					maxed = inst && inst.viewModel && inst.viewModel.isMaxed && inst.viewModel.isMaxed();
				var cards = list.map( function ( e, i ) {
					var who = entryText( e, N.nameField ) || ( cap( N.singular ) + ' ' + ( i + 1 ) ),
						what = N.productField ? productName( entryText( e, N.productField ) ) : '',
						price = toNumber( e.total ),
						asking = confirming === String( e.id );
					return '<div class="rx-person' + ( asking ? ' is-confirming' : '' ) + '" data-id="' + esc( e.id ) + '">' +
						'<span class="rx-person-num" aria-hidden="true">' + ( i + 1 ) + '</span>' +
						'<span class="rx-person-main"><strong>' + esc( who ) + '</strong>' +
						'<span>' + esc( [ what, price ? niceMoney( price ) : '' ].filter( Boolean ).join( ' · ' ) ) + '</span></span>' +
						( asking ?
							'<span class="rx-person-acts"><span class="rx-person-ask">Remove ' + esc( who.split( ' ' )[0] ) + '?</span>' +
							'<button type="button" class="rx-link is-danger" data-act="remove-yes">Remove</button>' +
							'<button type="button" class="rx-link" data-act="remove-no">Keep</button></span>' :
							'<span class="rx-person-acts">' +
							'<button type="button" class="rx-link" data-act="edit" aria-label="Edit ' + esc( who ) + '">Edit</button>' +
							'<button type="button" class="rx-link" data-act="remove" aria-label="Remove ' + esc( who ) + '">Remove</button></span>' ) +
						'</div>';
				} ).join( '' );
				var addLabel = list.length ? ( N.addAnother || 'Add another ' + N.singular ) : ( N.addFirst || 'Add a ' + N.singular );
				wrap.innerHTML = ( cards ? '<div class="rx-people">' + cards + '</div>' : '' ) +
					( maxed ? '<p class="rx-roster-max">That’s the most you can register at once.</p>' :
						'<button type="button" class="rx-add' + ( list.length ? '' : ' is-first' ) + '" data-act="add">' +
						'<span class="rx-add-plus" aria-hidden="true">+</span> ' + esc( addLabel ) + '</button>' );
			}

			wrap.addEventListener( 'click', function ( e ) {
				var b = e.target.closest( '[data-act]' ), inst = nestedInst();
				if ( ! b || ! inst ) {
					return;
				}
				var card = b.closest( '.rx-person' ), id = card ? card.dataset.id : null,
					entry = id ? nestedEntries().filter( function ( x ) { return String( x.id ) === id; } )[0] : null;
				clearFrameError();
				switch ( b.dataset.act ) {
					case 'add':
						NF.editN = 0;
						var add = field.querySelector( '.gpnf-add-entry' );
						if ( add ) {
							add.click();
						}
						break;
					case 'edit':
						NF.editN = nestedEntries().indexOf( entry ) + 1;
						inst.editEntry( id, $( b ) );
						break;
					case 'remove':
						confirming = id;
						render();
						break;
					case 'remove-no':
						confirming = null;
						render();
						break;
					case 'remove-yes':
						confirming = null;
						if ( entry ) {
							inst.deleteEntry( entry, $( b ) );
						}
						break;
				}
			} );

			whenNested( function ( inst ) {
				inst.viewModel.entries.subscribe( function () {
					render();
					recalc();
				} );
				render();
			} );
			render();

			f.check = function () {
				return nestedEntries().length >= ( N.min || 1 ) ? '' : ( N.minMessage || 'Add at least one ' + N.singular + ' to keep going.' );
			};
		}
	};

	// Plain option chips are the time chips without the day grouping.
	widgets.chips = widgets.slots;

	// "Half Marathon - $75" or GPNF's "10k ($55.00)" -> "Half Marathon" / "10k"
	function productName( text ) {
		return String( text ).replace( /\s*(?:[-\u2013\u2014:]\s*\$[\d.,]+|\(\s*\$[\d.,]+\s*\))\s*$/, '' );
	}

	function cap( s ) {
		s = String( s || '' );
		return s.charAt( 0 ).toUpperCase() + s.slice( 1 );
	}

	// ---------- GP Nested Forms ----------

	var N = C.nested || null;

	function nestedInst() {
		return N ? window[ 'GPNestedForms_' + FID + '_' + N.field ] || null : null;
	}

	function nestedEntries() {
		var inst = nestedInst();
		return inst && inst.viewModel && inst.viewModel.entries ? inst.viewModel.entries() : [];
	}

	// An entry's display text for a child field (GPNF labels can hold HTML, e.g. mailto links).
	function entryText( entry, id ) {
		var v = entry ? entry[ id ] : null;
		if ( ! v || v.label == null ) {
			return '';
		}
		var d = document.createElement( 'div' );
		d.innerHTML = String( v.label );
		return d.textContent.trim();
	}

	// GPNF builds its instance after the form renders; wait for it (up to ~6s).
	function whenNested( cb ) {
		var tries = 0;
		( function poll() {
			var inst = nestedInst();
			if ( inst && inst.viewModel && inst.viewModel.entries ) {
				cb( inst );
			} else if ( tries++ < 60 ) {
				setTimeout( poll, 100 );
			}
		} )();
	}

	function readField( id ) {
		var n = fieldEl( id );
		if ( ! n ) {
			return '';
		}
		var sel = n.querySelector( 'select' );
		if ( sel ) {
			var o = sel.options[ sel.selectedIndex ];
			return o && o.value ? o.text : '';
		}
		var checked = n.querySelectorAll( 'input:checked' );
		if ( checked.length ) {
			return Array.prototype.map.call( checked, function ( i ) {
				var l = n.querySelector( 'label[for="' + i.id + '"]' );
				return l ? l.textContent.trim() : i.value;
			} ).join( ', ' );
		}
		return Array.prototype.map.call( n.querySelectorAll( 'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], textarea' ), function ( i ) {
			return i.value.trim();
		} ).filter( Boolean ).join( ' ' );
	}

	function lineItems() {
		var items = [], shirtLabels = {};
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( cfg.shirts && cfg.shirts.map ) {
				Object.keys( cfg.shirts.map ).forEach( function ( size ) {
					shirtLabels[ cfg.shirts.map[ size ] ] = 'Shirt, ' + size;
				} );
			}
		} );
		// Which frame each field belongs to, for 'needs' (skipped runners) and 'nameField' (whose item it is).
		var owner = {}, skip = {};
		( C.frames || [] ).forEach( function ( cfg ) {
			( cfg.fields || [] ).forEach( function ( id ) {
				owner[ id ] = cfg;
			} );
		} );
		// Nested entries are listed one line per person; the parent's subtotal product would double them.
		if ( N ) {
			( N.controlled || [] ).forEach( function ( id ) {
				skip[ id ] = true;
			} );
			nestedEntries().forEach( function ( e, i ) {
				var who = entryText( e, N.nameField ) || ( cap( N.singular ) + ' ' + ( i + 1 ) ),
					what = N.productField ? productName( entryText( e, N.productField ) ) : '';
				items.push( { label: what ? who + ' · ' + what : who, qty: 1, price: toNumber( e.total ) } );
			} );
		}
		function push( id, item ) {
			item.id = id;
			items.push( item );
		}
		form.querySelectorAll( '.gfield--type-product' ).forEach( function ( n ) {
			var id = fieldId( n ), cfg = owner[ id ] || {};
			if ( skip[ id ] || ! isShown( n ) || ! counts( cfg ) ) {
				return;
			}
			var who = cfg.nameField ? readField( cfg.nameField ) : '',
				picked = n.querySelector( '.gfield_radio input[type="radio"]:checked' );
			if ( picked ) {
				var pl = n.querySelector( 'label[for="' + picked.id + '"]' ),
					name = productName( pl ? pl.textContent.trim() : picked.value.split( '|' )[0] );
				push( id, { label: who ? who + ' \u00b7 ' + name : name, qty: 1, price: toNumber( picked.value.split( '|' )[1] || 0 ) } );
				return;
			}
			if ( n.querySelector( '.gfield_radio' ) ) {
				return;
			}
			var sel = n.querySelector( 'select' );
			if ( sel ) {
				var o = sel.options[ sel.selectedIndex ];
				if ( o && o.value ) {
					push( id, { label: o.text, qty: 1, price: toNumber( ( o.value.split( '|' )[1] ) || 0 ) } );
				}
				return;
			}
			var base = n.querySelector( 'input[id^="ginput_base_price_"]' ),
				qtyInput = n.querySelector( 'input.ginput_quantity, input[type="number"]' ),
				qty = qtyInput ? parseInt( qtyInput.value, 10 ) || 0 : 1;
			if ( base && qty > 0 ) {
				var label = shirtLabels[ id ] || ( n.querySelector( '.gfield_label_product' ) || n.querySelector( '.gfield_label' ) || {} ).textContent || 'Item';
				push( id, { label: label.replace( /\*\s*$/, '' ).trim(), qty: qty, price: toNumber( base.value ) } );
			}
		} );

		// Free picks ("No" to an add-on, a $0 size) aren't purchases. Config 'lineItems' renames a
		// product's line ({ID} = another field's answer, e.g. "Hoodie ({58})") or drops it (false).
		var names = C.lineItems || {};
		return items.filter( function ( it ) {
			return it.price * it.qty !== 0 && names[ it.id ] !== false;
		} ).map( function ( it ) {
			if ( typeof names[ it.id ] === 'string' ) {
				it.label = names[ it.id ].replace( /\{(\d+)\}/g, function ( m, other ) {
					return readField( parseInt( other, 10 ) );
				} ).replace( /\s*\(\s*\)/, '' );
			}
			return it;
		} );
	}

	function summaryHtml( cfg ) {
		var recap = ( cfg.recap || [] ).map( function ( r ) {
			var v = readField( r.field );
			return v ? '<div class="rx-recap-row"><dt>' + esc( r.label ) + '</dt><dd>' + esc( v ) + '</dd></div>' : '';
		} ).join( '' );
		var items = lineItems().map( function ( it ) {
			return '<li><span>' + esc( it.label ) + ( it.qty > 1 ? ' <em>&times; ' + it.qty + '</em>' : '' ) + '</span><span>' + esc( money( it.price * it.qty ) ) + '</span></li>';
		} ).join( '' );
		return '<div class="rx-summary">' +
			( recap ? '<dl class="rx-recap">' + recap + '</dl>' : '' ) +
			( items ? '<ul class="rx-lines">' + items + '</ul>' : '' ) +
			'<div class="rx-sum-total"><span>Total</span><span class="rx-sum-total-val">' + esc( money( total ) ) + '</span></div>' +
			'</div>';
	}

	// ---------- validation (client side, mirrors GF's required rules) ----------

	var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

	// box: where frame-level messages go (the page's error area, or the nested modal's).
	function validateFrame( f, box ) {
		var firstBad = null;
		clearFrameError( box );
		f.fields.forEach( function ( n ) {
			if ( ! isShown( n ) || n.classList.contains( 'rx-controlled' ) ) {
				return;
			}
			var msg = fieldProblem( n );
			if ( msg ) {
				setFieldError( n, msg );
				firstBad = firstBad || n;
			} else {
				clearFieldError( n );
			}
		} );
		if ( ! firstBad && f.wantsShirts && f.wantsShirts() && f.shirtCount() === 0 ) {
			showFrameError( 'Add at least one shirt, or choose No.', box );
			return false;
		}
		var custom = ! firstBad && f.check ? f.check() : '';
		if ( custom ) {
			showFrameError( custom, box );
			return false;
		}
		if ( firstBad ) {
			var focusable = firstBad.querySelector( 'input:not([type="hidden"]), select, textarea, button' );
			if ( focusable ) {
				focusable.focus( { preventScroll: true } );
			}
			firstBad.scrollIntoView( { block: 'center', behavior: reduceMotion ? 'auto' : 'smooth' } );
			return false;
		}
		return true;
	}

	function fieldProblem( n ) {
		var t = fieldType( n ), required = n.classList.contains( 'gfield_contains_required' );
		if ( t === 'product' && n.querySelector( '.gfield_radio' ) ) {
			t = 'radio';
		}
		if ( t === 'stripe_creditcard' || t === 'html' || t === 'section' || t === 'total' || t === 'coupon' ) {
			return '';
		}
		if ( t === 'checkbox' || t === 'radio' || t === 'consent' ) {
			// GP Inventory removes every choice once they're gone and leaves its own message in their place.
			if ( required && ! n.querySelector( 'input[type="radio"], input[type="checkbox"]' ) ) {
				var gone = n.querySelector( '.ginput_container' );
				return ( gone && gone.textContent.trim() ) || 'There are no spots left for this one.';
			}
			return required && ! n.querySelector( 'input:checked' ) ? ( t === 'radio' ? 'Pick one to keep going.' : 'Please check this to continue.' ) : '';
		}
		var inputs = Array.prototype.filter.call( n.querySelectorAll( 'input, select, textarea' ), function ( i ) {
			return i.type !== 'hidden' && ! i.disabled && i.offsetParent !== null || ( i.tagName === 'SELECT' && n.classList.contains( 'rx-has-widget' ) );
		} );
		if ( required ) {
			var flagged = inputs.filter( function ( i ) { return i.getAttribute( 'aria-required' ) === 'true'; } );
			var must = flagged.length ? flagged : inputs;
			for ( var i = 0; i < must.length; i++ ) {
				if ( ! String( must[ i ].value || '' ).trim() ) {
					if ( n.classList.contains( 'rx-has-widget' ) ) {
						return 'Pick one to keep going.';
					}
					return inputs.length > 1 ? 'Please fill in every part of this.' : 'This one is required.';
				}
			}
		}
		if ( t === 'email' ) {
			var e = n.querySelector( 'input[type="email"], input' );
			if ( e && e.value.trim() && ! EMAIL_RE.test( e.value.trim() ) ) {
				return "That email address doesn't look quite right.";
			}
		}
		if ( t === 'phone' ) {
			var p = n.querySelector( 'input' );
			if ( p && p.value.trim() && p.value.replace( /\D/g, '' ).length < 10 ) {
				return 'Please include the full 10-digit number.';
			}
		}
		if ( t === 'number' ) {
			var num = n.querySelector( 'input' );
			if ( num && num.value.trim() && isNaN( toNumber( num.value ) ) ) {
				return 'Please enter a number.';
			}
		}
		return '';
	}

	function setFieldError( n, msg ) {
		n.classList.add( 'rx-invalid' );
		var m = n.querySelector( '.rx-field-msg' );
		if ( ! m ) {
			m = document.createElement( 'div' );
			m.className = 'rx-field-msg';
			m.setAttribute( 'role', 'alert' );
			n.appendChild( m );
		}
		m.textContent = msg;
		n.classList.remove( 'rx-shake' );
		void n.offsetWidth;
		n.classList.add( 'rx-shake' );
	}

	function clearFieldError( n ) {
		n.classList.remove( 'rx-invalid', 'rx-shake' );
		var m = n.querySelector( '.rx-field-msg' );
		if ( m ) {
			m.remove();
		}
	}

	function showFrameError( msg, box ) {
		box = box || el.error;
		box.textContent = msg;
		box.hidden = false;
		box.classList.remove( 'rx-shake' );
		void box.offsetWidth;
		box.classList.add( 'rx-shake' );
	}

	function clearFrameError( box ) {
		box = box || el.error;
		box.hidden = true;
		box.textContent = '';
	}

	// ---------- rendering ----------

	function assetUrl( file ) {
		return file ? C.assets + file : '';
	}

	function paint( f ) {
		var cfg = f.cfg;

		frames.forEach( function ( other ) {
			if ( other === f ) {
				return;
			}
			other.fields.forEach( function ( n ) {
				n.classList.add( 'rx-parked' );
				n.setAttribute( 'inert', '' );
			} );
			if ( other.extra ) {
				other.extra.classList.add( 'rx-parked' );
			}
		} );
		f.fields.forEach( function ( n ) {
			if ( ! n.classList.contains( 'rx-controlled' ) ) {
				n.classList.remove( 'rx-parked' );
				n.removeAttribute( 'inert' );
			}
		} );
		if ( f.extra ) {
			f.extra.classList.remove( 'rx-parked' );
		}

		el.kicker.textContent = cfg.kicker || ( 'Step ' + ( position() + 1 ) );
		el.title.textContent = fill( cfg.title, cfg );
		el.lede.textContent = fill( cfg.lede, cfg );
		el.lede.hidden = ! cfg.lede;

		el.tip.hidden = ! cfg.tip;
		el.tipBody.innerHTML = cfg.tip || '';

		el.widget.innerHTML = '';
		if ( f.onEnter ) {
			f.onEnter();
		}

		root.classList.toggle( 'rx-solo', !! cfg.solo );
		root.classList.toggle( 'rx-hide-desc', !! cfg.hideDesc );
		root.setAttribute( 'data-widget', cfg.widget || '' );

		if ( cfg.art ) {
			el.art.src = assetUrl( cfg.art );
			el.art.parentNode.hidden = false;
		} else {
			el.art.parentNode.hidden = true;
		}

		var last = page === lastPage && frameAt( 1 ) === -1;
		el.nextLabel.textContent = last ? ( cfg.submit || 'Submit' ) : 'Continue';
		el.next.classList.toggle( 'is-submit', last );
		el.back.hidden = page === 1 && frameAt( -1 ) === -1 && ! el.welcome;

		clearFrameError();
		updateProgress();
		updateSubmitLabel();
	}

	// Whether a configured screen on another GF page will be shown. GF runs conditional logic
	// for every page's fields up front, so a branch the visitor isn't on doesn't count.
	function cfgShown( cfg ) {
		if ( ! counts( cfg ) ) {
			return false;
		}
		var nodes = ( cfg.fields || [] ).map( function ( id ) { return fieldEl( id ); } ).filter( Boolean );
		return ! nodes.length || frameVisible( { fields: nodes } );
	}

	function position() {
		var before = 0;
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( ( cfg.page || 1 ) < page && cfgShown( cfg ) ) {
				before++;
			}
		} );
		return before + frames.slice( 0, Math.max( idx, 0 ) ).filter( frameVisible ).length;
	}

	function totalFrames() {
		var n = 0;
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( ( cfg.page || 1 ) !== page && cfgShown( cfg ) ) {
				n++;
			}
		} );
		return n + frames.filter( frameVisible ).length;
	}

	function updateProgress( pctOverride ) {
		var count = totalFrames(),
			pct = typeof pctOverride === 'number' ? pctOverride : Math.round( position() / count * 100 );
		el.progress.classList.add( 'is-visible' );
		el.progressFill.style.width = Math.max( pct, 2 ) + '%';
		el.progressPct.textContent = typeof pctOverride === 'number' ? 'All done' : 'Step ' + ( position() + 1 ) + ' of ' + count;
	}

	function animateIn( dir ) {
		el.inner.classList.remove( 'is-leaving', 'is-entering-fwd', 'is-entering-back' );
		void el.inner.offsetWidth;
		el.inner.classList.add( dir < 0 ? 'is-entering-back' : 'is-entering-fwd' );
	}

	function goTo( i, dir, opts ) {
		opts = opts || {};
		if ( i < 0 || i >= frames.length ) {
			return;
		}
		var swap = function () {
			idx = i;
			paint( frames[ i ] );
			checkTall();
			animateIn( dir );
			window.scrollTo( 0, 0 );
			if ( ! opts.noFocus ) {
				focusFrame( frames[ i ] );
			}
			busy = false;
		};
		if ( opts.instant || reduceMotion || el.frame.hidden ) {
			swap();
			return;
		}
		busy = true;
		el.inner.classList.remove( 'is-entering-fwd', 'is-entering-back' );
		el.inner.classList.add( 'is-leaving', dir < 0 ? 'to-right' : 'to-left' );
		setTimeout( function () {
			el.inner.classList.remove( 'is-leaving', 'to-right', 'to-left' );
			swap();
		}, 230 );
	}

	// Long screens pin the boat to the top instead of centering it (see .is-tall in race-reg.css).
	function checkTall() {
		el.inner.classList.toggle( 'is-tall', el.inner.querySelector( '.rx-copy' ).offsetHeight > window.innerHeight - 180 );
	}

	function focusFrame( f ) {
		setTimeout( function () {
			var first = null;
			f.fields.some( function ( n ) {
				if ( ! isShown( n ) || n.classList.contains( 'rx-controlled' ) ) {
					return false;
				}
				var i = n.querySelector( 'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], textarea' );
				if ( i && ! i.value ) {
					first = i;
				}
				return true;
			} );
			// Don't pop the phone keyboard over the question; desktop gets the cursor in the box.
			if ( first && window.matchMedia( '(min-width: 721px)' ).matches ) {
				first.focus( { preventScroll: true } );
			} else {
				el.head.focus( { preventScroll: true } );
			}
		}, reduceMotion ? 0 : 380 );
	}

	// ---------- navigation ----------

	function nativeButton( kind ) {
		if ( kind === 'submit' ) {
			return document.getElementById( 'gform_submit_button_' + FID );
		}
		var footer = pageEl.querySelector( '.gform_page_footer' ) || form;
		return footer.querySelector( kind === 'next' ? '.gform_next_button' : '.gform_previous_button' );
	}

	function loading( on ) {
		el.next.classList.toggle( 'is-loading', on );
		el.next.disabled = on;
		el.back.disabled = on;
	}

	function next() {
		if ( busy || idx < 0 ) {
			return;
		}
		var f = frames[ idx ];
		if ( ! validateFrame( f ) ) {
			return;
		}
		var j = frameAt( 1 );
		if ( j !== -1 ) {
			goTo( j, 1 );
			return;
		}
		var kind = page < lastPage ? 'next' : 'submit', btn = nativeButton( kind );
		if ( ! btn ) {
			return;
		}
		store.set( 'dir', 'fwd' );
		loading( true );
		if ( kind === 'submit' ) {
			// Stripe can reject the card without a page load; hand the button back if we're still here.
			setTimeout( function () {
				loading( false );
			}, 9000 );
		}
		btn.click();
	}

	function back() {
		if ( busy ) {
			return;
		}
		var j = frameAt( -1 );
		if ( j !== -1 ) {
			goTo( j, -1 );
			return;
		}
		if ( page > 1 ) {
			var btn = nativeButton( 'previous' );
			if ( btn ) {
				store.set( 'dir', 'back' );
				loading( true );
				btn.click();
			}
			return;
		}
		if ( el.welcome ) {
			showWelcome();
		}
	}

	function showWelcome() {
		root.dataset.view = 'welcome';
		el.frame.hidden = true;
		el.welcome.hidden = false;
		el.progress.classList.remove( 'is-visible' );
		el.welcome.classList.remove( 'is-in' );
		void el.welcome.offsetWidth;
		el.welcome.classList.add( 'is-in' );
		idx = -1;
		window.scrollTo( 0, 0 );
	}

	function showFrames( i, dir ) {
		root.dataset.view = 'frames';
		if ( el.welcome ) {
			el.welcome.hidden = true;
		}
		el.frame.hidden = false;
		goTo( i, dir || 1, { instant: true, noFocus: dir === 0 } );
	}

	function showDone( conf ) {
		root.dataset.state = 'ready';
		root.dataset.view = 'done';
		el.frame.hidden = true;
		if ( el.welcome ) {
			el.welcome.hidden = true;
		}
		el.done.hidden = false;
		el.doneMsg.appendChild( conf );
		el.done.classList.add( 'is-in' );
		updateProgress( 100 );
		store.del( 'dir' );
	}

	// ---------- total ----------

	function setTotal( n ) {
		total = toNumber( n );
		el.totalValue.textContent = money( total );
		el.total.hidden = ! ( total > 0 );
		var sumVal = root.querySelector( '.rx-sum-total-val' );
		if ( sumVal ) {
			sumVal.textContent = money( total );
		}
		updateSubmitLabel();
	}

	function updateSubmitLabel() {
		if ( ! el.next.classList.contains( 'is-submit' ) || idx < 0 ) {
			return;
		}
		var base = frames[ idx ].cfg.submit || 'Submit';
		el.nextLabel.textContent = total > 0 ? base + ' · ' + money( total ) : base;
	}

	function readTotalField() {
		var t = form.querySelector( '.gfield--type-total input, .ginput_container_total input' );
		if ( t && t.value !== '' ) {
			setTotal( t.value );
		}
	}

	// ---------- GP Nested Forms modal: the child form, one question at a time ----------
	//
	// GPNF builds the modal (Tingle) once and swaps the child form's HTML in on every open; a
	// server-side validation error re-renders just the form inside it. So the flow is rebuilt
	// for every new <form> element, and our head/nav live beside the form so they survive.

	var NF = { modal: null, form: null, frames: [], idx: 0, editN: 0, ui: null, timer: null };

	function nestedModal() {
		return N ? document.querySelector( '.gpnf-modal-' + FID + '-' + N.field ) : null;
	}

	function nfShell( modal ) {
		var box = modal.querySelector( '.tingle-modal-box__content' );
		if ( ! box ) {
			return null;
		}
		var ui = NF.ui && box.contains( NF.ui.head ) ? NF.ui : null;
		if ( ui ) {
			return ui;
		}
		var head = document.createElement( 'div' );
		head.className = 'rx-mtop';
		head.innerHTML =
			'<div class="rx-mbar"><span class="rx-mstep"></span><span class="rx-mprogress" aria-hidden="true"><span></span></span></div>' +
			( N.art ? '<img class="rx-mart" alt="" src="' + esc( assetUrl( N.art ) ) + '">' : '' ) +
			'<div class="rx-head" tabindex="-1"><div class="rx-kicker"></div><h2 class="rx-h2"></h2><p class="rx-lede"></p></div>';
		var foot = document.createElement( 'div' );
		foot.className = 'rx-mfoot';
		foot.innerHTML =
			'<div class="rx-tip" hidden><svg class="rx-tip-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-4.9 12 6.9 6.9 0 0 1 1.6 2.6l.1.4h6.4l.1-.4a6.9 6.9 0 0 1 1.6-2.6A7 7 0 0 0 12 2zm-2.5 17a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-.5h-5v.5zm1.5 3a1.5 1.5 0 0 0 2 0 1.6 1.6 0 0 0 .4-1h-2.8c0 .4.1.7.4 1z"/></svg><div class="rx-tip-body"></div></div>' +
			'<div class="rx-error" role="alert" hidden></div>' +
			'<div class="rx-nav rx-mnav">' +
			'<button type="button" class="rx-back">&larr; Back</button>' +
			'<button type="button" class="rx-link rx-msave" hidden>Save changes</button>' +
			'<button type="button" class="rx-btn"><span class="rx-next-label">Continue</span> <span class="rx-arrow" aria-hidden="true">&rarr;</span></button>' +
			'</div>';
		box.insertBefore( head, box.firstChild );
		box.appendChild( foot );

		ui = {
			head: head,
			foot: foot,
			step: head.querySelector( '.rx-mstep' ),
			bar: head.querySelector( '.rx-mprogress > span' ),
			focus: head.querySelector( '.rx-head' ),
			kicker: head.querySelector( '.rx-kicker' ),
			title: head.querySelector( '.rx-h2' ),
			lede: head.querySelector( '.rx-lede' ),
			tip: foot.querySelector( '.rx-tip' ),
			tipBody: foot.querySelector( '.rx-tip-body' ),
			error: foot.querySelector( '.rx-error' ),
			back: foot.querySelector( '.rx-back' ),
			save: foot.querySelector( '.rx-msave' ),
			next: foot.querySelector( '.rx-btn' ),
			nextLabel: foot.querySelector( '.rx-next-label' )
		};
		ui.next.addEventListener( 'click', nfNext );
		ui.back.addEventListener( 'click', nfBack );
		ui.save.addEventListener( 'click', nfSaveNow );
		NF.ui = ui;
		return ui;
	}

	function nfEditing() {
		var inst = nestedInst();
		return !! inst && typeof inst.getMode === 'function' && inst.getMode() === 'edit';
	}

	function nfInit() {
		var modal = nestedModal();
		if ( ! modal ) {
			return;
		}
		// Inside #rx so the form styles and the parked-field rules apply to the child form too.
		if ( modal.parentNode !== root ) {
			root.appendChild( modal );
		}
		modal.classList.add( 'rx-modal' );
		var form = modal.querySelector( '#gform_' + N.formId );
		if ( ! form || form === NF.form || ! modal.classList.contains( 'tingle-modal--visible' ) ) {
			return;
		}
		var rerender = !! NF.form && !! NF.ui && modal.contains( NF.ui.head );
		NF.modal = modal;
		NF.form = form;
		nfLoading( false );

		var ui = nfShell( modal );
		if ( ! ui ) {
			return;
		}
		var cfgs = ( N.frames || [] ).map( function ( cfg ) {
			return $.extend( { fid: N.formId, nameField: N.nameField, nameFallback: N.nameFallback }, cfg );
		} );
		NF.frames = collectFrames( form, cfgs, N.formId );
		NF.frames.forEach( function ( f ) {
			if ( f.cfg.widget && widgets[ f.cfg.widget ] && f.cfg.widget !== 'roster' && f.cfg.widget !== 'summary' ) {
				widgets[ f.cfg.widget ]( f );
			}
		} );

		form.addEventListener( 'keydown', function ( e ) {
			if ( e.key !== 'Enter' || e.isComposing ) {
				return;
			}
			var t = e.target;
			if ( t.tagName === 'TEXTAREA' || t.tagName === 'BUTTON' || t.type === 'submit' || t.type === 'button' ) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			nfNext();
		}, true );
		form.addEventListener( 'input', function ( e ) {
			var n = e.target.closest( '.gfield' );
			if ( n && n.classList.contains( 'rx-invalid' ) ) {
				clearFieldError( n );
			}
		} );
		form.addEventListener( 'change', function ( e ) {
			var n = e.target.closest( '.gfield' );
			if ( n && n.classList.contains( 'rx-invalid' ) && ! fieldProblem( n ) ) {
				clearFieldError( n );
			}
		} );

		// Land on the first screen, or on the first field GF rejected after a submit.
		var land = 0;
		if ( rerender ) {
			NF.frames.some( function ( f, i ) {
				if ( f.fields.some( function ( n ) { return n.classList.contains( 'gfield_error' ); } ) ) {
					land = i;
					return true;
				}
				return false;
			} );
		}
		NF.idx = Math.max( 0, stepFrom( NF.frames, -1, 1 ) );
		if ( land ) {
			NF.idx = land;
		}
		nfPaint( 1 );
		if ( rerender ) {
			var gfMsg = form.querySelector( '.gform_validation_errors' );
			if ( gfMsg && ! NF.frames[ NF.idx ].fields.some( function ( n ) { return n.querySelector( '.validation_message' ); } ) ) {
				showFrameError( gfMsg.textContent.trim(), ui.error );
			}
		}
	}

	function nfPaint( dir ) {
		var ui = NF.ui, f = NF.frames[ NF.idx ];
		if ( ! ui || ! f ) {
			return;
		}
		var cfg = f.cfg, editing = nfEditing(),
			n = editing ? ( NF.editN || 1 ) : nestedEntries().length + 1;

		NF.frames.forEach( function ( other ) {
			if ( other !== f ) {
				other.fields.forEach( function ( x ) {
					x.classList.add( 'rx-parked' );
					x.setAttribute( 'inert', '' );
				} );
			}
		} );
		f.fields.forEach( function ( x ) {
			if ( ! x.classList.contains( 'rx-controlled' ) ) {
				x.classList.remove( 'rx-parked' );
				x.removeAttribute( 'inert' );
			}
		} );

		var who = cap( N.singular ) + ' ' + n;
		ui.kicker.textContent = ( cfg.kicker || '{who}' ).replace( /\{who\}/g, editing ? 'Editing ' + who.toLowerCase() : who );
		ui.title.textContent = fill( cfg.title, cfg );
		ui.lede.textContent = fill( cfg.lede, cfg );
		ui.lede.hidden = ! cfg.lede;
		ui.tip.hidden = ! cfg.tip;
		ui.tipBody.innerHTML = cfg.tip || '';
		clearFrameError( ui.error );

		var shown = NF.frames.filter( frameVisible ), pos = shown.indexOf( f );
		ui.step.textContent = 'Step ' + ( pos + 1 ) + ' of ' + shown.length;
		ui.bar.style.width = Math.max( 4, Math.round( ( pos + 1 ) / shown.length * 100 ) ) + '%';

		var last = stepFrom( NF.frames, NF.idx, 1 ) === -1;
		ui.nextLabel.textContent = last ? ( editing ? ( N.saveLabel || 'Save changes' ) : ( N.addLabel || 'Add ' + N.singular ) ) : 'Continue';
		ui.next.classList.toggle( 'is-submit', last );
		ui.back.innerHTML = stepFrom( NF.frames, NF.idx, -1 ) === -1 ? 'Cancel' : '&larr; Back';
		ui.save.hidden = ! editing || last;

		NF.modal.classList.toggle( 'rx-solo', !! cfg.solo );
		NF.modal.classList.toggle( 'rx-hide-desc', !! cfg.hideDesc );

		var box = NF.modal.querySelector( '.tingle-modal-box' );
		if ( box && ! reduceMotion ) {
			box.classList.remove( 'is-entering-fwd', 'is-entering-back' );
			void box.offsetWidth;
			box.classList.add( dir < 0 ? 'is-entering-back' : 'is-entering-fwd' );
		}
		NF.modal.scrollTop = 0;
		setTimeout( function () {
			var first = null;
			f.fields.some( function ( x ) {
				if ( ! isShown( x ) || x.classList.contains( 'rx-controlled' ) ) {
					return false;
				}
				var i = x.querySelector( 'input[type="text"], input[type="email"], input[type="tel"], input[type="number"], textarea' );
				if ( i && ! i.value ) {
					first = i;
				}
				return true;
			} );
			if ( first && window.matchMedia( '(min-width: 721px)' ).matches ) {
				first.focus( { preventScroll: true } );
			} else {
				ui.focus.focus( { preventScroll: true } );
			}
		}, reduceMotion ? 0 : 320 );
	}

	function nfLoading( on ) {
		if ( ! NF.ui ) {
			return;
		}
		NF.ui.next.classList.toggle( 'is-loading', on );
		NF.ui.next.disabled = on;
		NF.ui.back.disabled = on;
		NF.ui.save.disabled = on;
		clearTimeout( NF.timer );
		if ( on ) {
			// Nothing came back (network trouble): hand the buttons back.
			NF.timer = setTimeout( function () { nfLoading( false ); }, 15000 );
		}
	}

	function nfSubmit() {
		var btn = NF.modal && NF.modal.querySelector( '.tingle-modal-box__footer .gpnf-btn-submit, .tingle-modal-box__footer .gpnf-btn-next' );
		if ( ! btn ) {
			return;
		}
		nfLoading( true );
		btn.click(); // GPNF submits the child form; success closes the modal, an error re-renders it
	}

	function nfNext() {
		var f = NF.frames[ NF.idx ];
		if ( ! f || NF.ui.next.disabled || ! validateFrame( f, NF.ui.error ) ) {
			return;
		}
		var j = stepFrom( NF.frames, NF.idx, 1 );
		if ( j !== -1 ) {
			NF.idx = j;
			nfPaint( 1 );
			return;
		}
		nfSubmit();
	}

	// Editing: check every screen, jump to the first one that needs attention, or save straight away.
	function nfSaveNow() {
		for ( var i = 0; i < NF.frames.length; i++ ) {
			var f = NF.frames[ i ];
			if ( frameVisible( f ) && f.fields.some( function ( n ) { return isShown( n ) && ! n.classList.contains( 'rx-controlled' ) && fieldProblem( n ); } ) ) {
				NF.idx = i;
				nfPaint( 1 );
				validateFrame( f, NF.ui.error );
				return;
			}
		}
		nfSubmit();
	}

	function nfBack() {
		var j = stepFrom( NF.frames, NF.idx, -1 );
		if ( j !== -1 ) {
			NF.idx = j;
			nfPaint( -1 );
			return;
		}
		var cancel = NF.modal && NF.modal.querySelector( '.tingle-modal-box__footer .gpnf-btn-cancel' );
		if ( ! cancel ) {
			return;
		}
		cancel.click();
		// GPNF asks "Are you sure?" on its own button when the form has changes; show the same here.
		setTimeout( function () {
			if ( NF.ui && cancel.classList.contains( 'tingle-btn--danger' ) ) {
				NF.ui.back.textContent = cancel.textContent;
				NF.ui.back.classList.add( 'is-danger' );
				setTimeout( function () {
					if ( NF.ui ) {
						NF.ui.back.textContent = 'Cancel';
						NF.ui.back.classList.remove( 'is-danger' );
					}
				}, 3000 );
			}
		}, 0 );
	}

	function nfWire() {
		if ( ! N ) {
			return;
		}
		var later = function () { setTimeout( nfInit, 0 ); };
		$( document ).on( 'gform_post_render gpnf_post_render', function ( e, formId ) {
			if ( parseInt( formId, 10 ) === parseInt( N.formId, 10 ) ) {
				later();
			}
		} );
		if ( window.gform && typeof window.gform.addAction === 'function' ) {
			window.gform.addAction( 'gpnf_modal_opened', later );
			window.gform.addAction( 'gpnf_modal_closed', function () {
				NF.form = null;
				NF.ui = null;
				NF.editN = 0;
				clearTimeout( NF.timer );
			} );
		}
		// The modal is built at page load; move it into #rx right away so it never flashes unstyled.
		whenNested( function () {
			var m = nestedModal();
			if ( m && m.parentNode !== root ) {
				m.classList.add( 'rx-modal' );
				root.appendChild( m );
			}
		} );
	}

	// ---------- boot ----------

	function wire() {
		el.next.addEventListener( 'click', next );
		el.back.addEventListener( 'click', back );
		if ( el.start ) {
			el.start.addEventListener( 'click', function () {
				idx = -1;
				var first = frameAt( 1 );
				showFrames( first === -1 ? 0 : first, 1 );
			} );
		}

		// Enter moves to the next screen instead of submitting the whole GF page.
		form.addEventListener( 'keydown', function ( e ) {
			if ( e.key !== 'Enter' || e.isComposing ) {
				return;
			}
			var t = e.target;
			if ( t.tagName === 'TEXTAREA' || t.tagName === 'BUTTON' || t.type === 'submit' || t.type === 'button' ) {
				return;
			}
			e.preventDefault();
			e.stopPropagation();
			var coupon = t.closest( '.gfield--type-coupon' );
			if ( coupon ) {
				var apply = coupon.querySelector( 'input[type="button"], button' );
				if ( apply ) {
					apply.click();
				}
				return;
			}
			next();
		}, true );

		// Typing clears that field's error.
		form.addEventListener( 'input', function ( e ) {
			var n = e.target.closest( '.gfield' );
			if ( n && n.classList.contains( 'rx-invalid' ) ) {
				clearFieldError( n );
			}
		} );
		form.addEventListener( 'change', function ( e ) {
			var n = e.target.closest( '.gfield' );
			if ( n && n.classList.contains( 'rx-invalid' ) && ! fieldProblem( n ) ) {
				clearFieldError( n );
			}
			// An answer can change how many screens are left (e.g. the number of runners).
			if ( idx >= 0 && root.dataset.view === 'frames' ) {
				updateProgress();
			}
		} );

		if ( window.ResizeObserver ) {
			new ResizeObserver( checkTall ).observe( el.inner.querySelector( '.rx-copy' ) );
		}

		// GF runs conditional logic after the first paint; recount the screens once it has.
		$( document ).on( 'gform_post_conditional_logic', function ( e, formId ) {
			if ( parseInt( formId, 10 ) === FID && idx >= 0 && root.dataset.view === 'frames' ) {
				updateProgress();
			}
		} );

		if ( window.gform && typeof window.gform.addFilter === 'function' ) {
			window.gform.addFilter( 'gform_product_total', function ( t, formId ) {
				if ( parseInt( formId, 10 ) === FID ) {
					setTimeout( function () { setTotal( t ); }, 0 );
				}
				return t;
			}, 200 );
		}
	}

	function init() {
		if ( initialized ) {
			return;
		}
		initialized = true;

		if ( C.previewDone ) {
			var preview = document.createElement( 'div' );
			preview.innerHTML = C.previewDone;
			showDone( preview );
			return;
		}

		var conf = document.querySelector( '#gform_confirmation_message_' + FID + ', .gform_confirmation_message_' + FID );
		if ( conf ) {
			showDone( conf.closest( '.gform_confirmation_wrapper' ) || conf );
			return;
		}

		form = document.getElementById( 'gform_' + FID );
		if ( ! form ) {
			// Closed, full, or not rendered: leave GF's own message on screen.
			root.dataset.state = 'off';
			return;
		}

		// GF adds #gf_{id} to the action URL so the browser jumps to the form; the flow handles scrolling itself.
		if ( window.location.hash === '#gf_' + FID && window.history.replaceState ) {
			window.history.replaceState( null, '', window.location.pathname + window.location.search );
		}

		findPage();
		buildFrames();
		frames.forEach( function ( f ) {
			if ( f.cfg.widget && widgets[ f.cfg.widget ] ) {
				widgets[ f.cfg.widget ]( f );
			}
		} );
		wire();
		nfWire();
		readTotalField();
		root.dataset.state = 'ready';

		// GF's buttons are parked off-screen (see race-reg.css); keep them out of the tab order.
		form.querySelectorAll( '.gform_page_footer, .gform_footer' ).forEach( function ( n ) {
			n.setAttribute( 'aria-hidden', 'true' );
			n.querySelectorAll( 'input, button' ).forEach( function ( b ) {
				b.setAttribute( 'tabindex', '-1' );
			} );
		} );

		if ( ! frames.length ) {
			root.dataset.state = 'off';
			return;
		}

		// Where to land: a server-side error, the end of the page when coming back, the welcome screen, or the top.
		var dir = store.get( 'dir' );
		store.del( 'dir' );
		var errorAt = -1;
		frames.some( function ( f, i ) {
			if ( f.fields.some( function ( n ) { return n.classList.contains( 'gfield_error' ); } ) ) {
				errorAt = i;
				return true;
			}
			return false;
		} );

		idx = -1;
		if ( errorAt !== -1 ) {
			showFrames( errorAt, 0 );
			var gfMsg = form.querySelector( '.gform_validation_errors' );
			if ( gfMsg && ! frames[ errorAt ].fields.some( function ( n ) { return n.querySelector( '.validation_message' ); } ) ) {
				showFrameError( gfMsg.textContent.trim() );
			}
			return;
		}
		if ( form.querySelector( '.gform_validation_errors' ) ) {
			// A form-level error (e.g. payment) with no field to point at: show it on the last screen.
			idx = frames.length;
			var lastVisible = frameAt( -1 );
			idx = -1;
			showFrames( lastVisible === -1 ? 0 : lastVisible, 0 );
			showFrameError( form.querySelector( '.gform_validation_errors' ).textContent.trim() );
			return;
		}
		if ( dir === 'back' ) {
			idx = frames.length;
			var lastShown = frameAt( -1 );
			idx = -1;
			showFrames( lastShown === -1 ? 0 : lastShown, -1 );
			return;
		}
		if ( page === 1 && el.welcome && ! C.isPost ) {
			showWelcome();
			return;
		}
		var firstShown = frameAt( 1 );
		showFrames( firstShown === -1 ? 0 : firstShown, 1 );
	}

	$( document ).on( 'gform_post_render', function ( e, formId ) {
		if ( parseInt( formId, 10 ) === FID ) {
			setTimeout( init, 0 );
		}
	} );
	// Confirmation pages and GF builds that don't fire gform_post_render.
	$( window ).on( 'load', function () {
		setTimeout( init, 60 );
	} );
} )( jQuery );
