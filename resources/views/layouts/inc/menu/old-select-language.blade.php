<style>
	#search-list {
		background-position: 10px 12px;
		background-repeat: no-repeat;
		width: 100%;
		font-size: 14px;
		padding: 12px 20px 12px 10px;
		border: 1px solid #ddd;
	}

	.show-grid {
		display: grid;
	}

	.hide-grid {
		display: none;
	}

	#langMenuDropdown {
		list-style-type: none;
		padding: 10px;
		margin: 0;
		max-height: 400px;
		overflow-y: auto;
		overflow-x: hidden;
		grid-template-columns: repeat(3, 1fr);
		grid-gap: 10px;
	}

	#langMenuDropdown li a {
		border: 1px solid #ddd;
		margin-top: -1px; /* Prevent double borders */
		background-color: #f6f6f6;
		padding: 12px;
		text-decoration: none;
		font-size: 18px;
		color: black;
		display: block
	}

	#langMenuDropdown li a:hover:not(.header) {
		background-color: #eee;
	}
</style>
@if (is_array(getSupportedLanguages()) && count(getSupportedLanguages()) > 1)
	<!-- Language Selector -->
	<li class="dropdown lang-menu nav-item">
		<a type="button" class="btn btn-secondary" id="menu-button">
			<span class="lang-title">{{ strtoupper(config('app.locale')) }}</span>
		</a>


	<ul id="langMenuDropdown" class="dropdown-menu dropdown-menu-right user-menu shadow-sm hide-grid" role="menu">
		<input type="text" id="search-list" onkeyup="myFunction()" placeholder="Search ..." title="Type in a Language">
		@foreach(getSupportedLanguages() as $langCode => $lang)
				@continue(strtolower($langCode) == strtolower(config('app.locale')))
				<li class="dropdown-item">
					<a onclick="updateLocale('{{ url('lang/' . $langCode) }}')" href="#" tabindex="-1" rel="alternate" hreflang="{{ $langCode }}">
						<span class="lang-name">{!! $lang['native'] !!}</span>
					</a>
				</li>
			@endforeach
		</ul>
	</li>
@endif

<script src="{{ asset('vendor/admin-theme/plugins/jquery/jquery.min.js') }}"></script>
<script>
	function myFunction() {
		var input, filter, ul, li, a, i, txtValue;
		input = document.getElementById("search-list");
		filter = input.value.toUpperCase();
		ul = document.getElementById("langMenuDropdown");
		li = ul.getElementsByTagName("li");
		for (i = 0; i < li.length; i++) {
			a = li[i].getElementsByTagName("a")[0];
			txtValue = a.textContent || a.innerText;
			if (txtValue.toUpperCase().indexOf(filter) > -1) {
				li[i].style.display = "";
			} else {
				li[i].style.display = "none";
			}
		}
	}

	$( document ).ready(function() {
		var menubutton = document.getElementById("menu-button");
		menubutton.addEventListener('click', function() {
			if ($('.user-menu.hide-grid').length) {
				$('#langMenuDropdown').removeClass('hide-grid');
				$('#langMenuDropdown').addClass('show-grid');
			}
		}, false);

		$(document).mouseup(function(e)
		{
			var container = $("#langMenuDropdown");
			if (!container.is(e.target) && container.has(e.target).length === 0)
			{
				$('#langMenuDropdown').removeClass('show-grid');
				$('#langMenuDropdown').addClass('hide-grid');
			}
		});
	});


</script>