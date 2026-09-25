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

	function fieldEl( id ) {
		return document.getElementById( 'field_' + FID + '_' + id );
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
	}

	function recalc() {
		if ( typeof window.gformCalculateTotalPrice === 'function' ) {
			window.gformCalculateTotalPrice( FID );
		}
	}

	function frameAt( offset ) {
		for ( var i = idx + offset; i >= 0 && i < frames.length; i += offset ) {
			if ( frameVisible( frames[ i ] ) ) {
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
		var nodes = Array.prototype.slice.call( pageEl.querySelectorAll( '.gfield' ) ).filter( function ( n ) {
			return fieldId( n ) !== null;
		} );
		var claimed = {};

		frames = [];
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( ( cfg.page || 1 ) !== page ) {
				return;
			}
			var fields = [];
			( cfg.fields || [] ).forEach( function ( id ) {
				var n = fieldEl( id );
				if ( n && pageEl.contains( n ) ) {
					fields.push( n );
					claimed[ id ] = true;
				}
			} );
			if ( fields.length ) {
				frames.push( { cfg: cfg, fields: fields } );
			}
		} );

		// Anything the config doesn't mention still gets shown somewhere.
		nodes.forEach( function ( n ) {
			var id = fieldId( n ), t = fieldType( n );
			if ( claimed[ id ] || t === 'page' || t === 'hidden' || n.classList.contains( 'gfield_visibility_hidden' ) ) {
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
	}

	// ---------- custom controls ----------

	var widgets = {

		// Big clickable cards for a select (e.g. division product).
		cards: function ( f ) {
			var field = f.fields[0], select = field.querySelector( 'select' );
			if ( ! select ) {
				return;
			}
			var wrap = document.createElement( 'div' );
			wrap.className = 'rx-cards';
			wrap.setAttribute( 'role', 'radiogroup' );
			Array.prototype.forEach.call( select.options, function ( opt ) {
				if ( ! opt.value ) {
					return;
				}
				var parts = opt.value.split( '|' ),
					price = parts.length > 1 ? toNumber( parts[1] ) : null,
					desc = ( f.cfg.cards || {} )[ opt.text ] || '',
					b = document.createElement( 'button' );
				b.type = 'button';
				b.className = 'rx-card';
				b.setAttribute( 'role', 'radio' );
				b.dataset.value = opt.value;
				b.disabled = opt.disabled;
				b.innerHTML = '<span class="rx-card-check" aria-hidden="true"></span>' +
					'<span class="rx-card-title">' + esc( opt.text ) + '</span>' +
					( desc ? '<span class="rx-card-desc">' + esc( desc ) + '</span>' : '' ) +
					( price !== null ? '<span class="rx-card-price">' + esc( niceMoney( price ) ) + '</span>' : '' );
				b.addEventListener( 'click', function () {
					select.value = opt.value;
					change( select );
					sync();
					clearFieldError( field );
				} );
				wrap.appendChild( b );
			} );
			function sync() {
				Array.prototype.forEach.call( wrap.children, function ( b ) {
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

		// Day-grouped time chips for a select whose options read "Day at Time".
		slots: function ( f ) {
			var field = f.fields[0], select = field.querySelector( 'select' );
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
					time = m ? m[2] : opt.text;
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
					b.innerHTML = esc( s.time.replace( /\s*(am|pm)$/i, ' $1' ) ) + ( s.opt.disabled ? ' <small>Full</small>' : '' );
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
		}
	};

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
		form.querySelectorAll( '.gfield--type-product' ).forEach( function ( n ) {
			if ( ! isShown( n ) ) {
				return;
			}
			var id = fieldId( n ), sel = n.querySelector( 'select' );
			if ( sel ) {
				var o = sel.options[ sel.selectedIndex ];
				if ( o && o.value ) {
					items.push( { label: o.text, qty: 1, price: toNumber( ( o.value.split( '|' )[1] ) || 0 ) } );
				}
				return;
			}
			var base = n.querySelector( 'input[id^="ginput_base_price_"]' ),
				qtyInput = n.querySelector( 'input.ginput_quantity, input[type="number"]' ),
				qty = qtyInput ? parseInt( qtyInput.value, 10 ) || 0 : 1;
			if ( base && qty > 0 ) {
				var label = shirtLabels[ id ] || ( n.querySelector( '.gfield_label_product' ) || n.querySelector( '.gfield_label' ) || {} ).textContent || 'Item';
				items.push( { label: label.trim(), qty: qty, price: toNumber( base.value ) } );
			}
		} );
		return items;
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

	function validateFrame( f ) {
		var firstBad = null;
		clearFrameError();
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
			showFrameError( 'Add at least one shirt, or choose No.' );
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
		if ( t === 'stripe_creditcard' || t === 'html' || t === 'section' || t === 'total' || t === 'coupon' ) {
			return '';
		}
		if ( t === 'checkbox' || t === 'radio' || t === 'consent' ) {
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

	function showFrameError( msg ) {
		el.error.textContent = msg;
		el.error.hidden = false;
		el.error.classList.remove( 'rx-shake' );
		void el.error.offsetWidth;
		el.error.classList.add( 'rx-shake' );
	}

	function clearFrameError() {
		el.error.hidden = true;
		el.error.textContent = '';
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
		el.title.textContent = cfg.title || '';
		el.lede.textContent = cfg.lede || '';
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

	function position() {
		var before = 0;
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( ( cfg.page || 1 ) < page ) {
				before++;
			}
		} );
		return before + Math.max( idx, 0 );
	}

	function totalFrames() {
		var n = 0;
		( C.frames || [] ).forEach( function ( cfg ) {
			if ( ( cfg.page || 1 ) !== page ) {
				n++;
			}
		} );
		return n + frames.length;
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
		} );

		if ( window.ResizeObserver ) {
			new ResizeObserver( checkTall ).observe( el.inner.querySelector( '.rx-copy' ) );
		}

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
