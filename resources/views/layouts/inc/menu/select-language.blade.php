<style>
	#langModal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

#langModalContent {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 70%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

#langModalHeader {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

#langModalHeader .lang-title {
    font-size: 15px;
    font-weight: bold;
}

#langModalHeader .close-icon {
    cursor: pointer;
    font-size: 18px;
    color: #888;
}
.searchBox{
    width: 27%;
	padding: 10px;
	margin:10px 20px;
}
#langMenuDropdown {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 items per row */
    gap: 10px;
    list-style-type: none;
    padding: 0;
    margin: 0;
    max-height: 300px;
    overflow: auto;
}

#langMenuDropdown li a {
    border: 1px solid #ddd;
    margin-top: -1px; /* Prevent double borders */
    background-color: #f6f6f6;
    padding: 12px;
    text-decoration: none;
    font-size: 18px;
    color: black;
    display: block;
    text-align: center; /* Center the text */
    cursor: pointer;
}

#langMenuDropdown li a:hover:not(.header) {
    background-color: #eee;
}
</style>

@if (is_array(getSupportedLanguages()) && count(getSupportedLanguages()) > 1)
<!-- Language Selector -->
<li class="nav-item dropdown lang-menu">
    <a type="button" class="btn btn-secondary" id="menu-button">
        <span class="lang-title">{{ strtoupper(config('app.locale')) }}</span>
    </a>

    <ul id="langModal" class="hide-grid">
        <div id="langModalContent">
            <div id="langModalHeader">
                <span class="lang-title h4">Select Language</span>
				<button type="button" class="close-icon" data-dismiss="modal">
					<span aria-hidden="true">&times;</span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
            </div>
			<br>
            <div class="search-container">
                <input class="searchBox" type="text" id="search-list" onkeyup="myFunction()" placeholder="Search ..." title="Type in a Language">
                <span class="lang-title h6">{{ t('search_box_heading') }}</span>
            </div>
            <ul id="langMenuDropdown">
			
                @foreach(getSupportedLanguages() as $langCode => $lang)
                    @continue(strtolower($langCode) == strtolower(config('app.locale')))
                    <li class="dropdown-item">
                        <a onclick="updateLocale('{{ url('lang/' . $langCode) }}')" href="#" tabindex="-1" rel="alternate" hreflang="{{ $langCode }}">
                        <span class="lang-name">{!! $lang['native'] . ' -' . $lang['abbr']  !!}</span>

                      </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </ul>
</li>
@endif

<script src="{{ asset('vendor/admin-theme/plugins/jquery/jquery.min.js') }}"></script>
<script>
    function myFunction() {
        var input, filter, ul, li, i, txtValueNative, txtValueAbbr;
        input = document.getElementById("search-list");
        filter = input.value.toUpperCase();
        ul = document.getElementById("langMenuDropdown");
        li = ul.getElementsByTagName("li");
        
        for (i = 0; i < li.length; i++) {
            var a = li[i].getElementsByTagName("a")[0];
            var langName = a.querySelector('.lang-name');
            if (langName) {
                txtValueNative = langName.textContent.split(' -')[0] || '';
                txtValueAbbr = langName.textContent.split(' -')[1] || '';
                
                if (
                    txtValueNative.toUpperCase().indexOf(filter) > -1 || 
                    txtValueAbbr.toUpperCase().indexOf(filter) > -1
                ) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    }

    $(document).ready(function() {
        var menubutton = document.getElementById("menu-button");
        menubutton.addEventListener('click', function() {
            $('#langModal').fadeIn(); 
        });

        $(document).on('click', '.close-icon', function() {
            $('#langModal').fadeOut(); 
        });

        $(document).mouseup(function(e) {
            var container = $("#langModalContent");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                $('#langModal').fadeOut(); 
            }
        });

        $('#langModal .dropdown-item a').click(function() {
            $('#langModal').fadeOut();
        });
    });
</script>
