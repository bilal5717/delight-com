<?php
// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.country_flag_activation')) {
    if (!empty(config('country.code'))) {
        if (file_exists(public_path() . '/images/flags/24/' . config('country.icode') . '.png')) {
            $multiCountriesIsEnabled = true;
            $multiCountriesLabel = 'title="' . t('Select a Country') . '"';
        }
    }
}
?>

<div class="topbar">
    <div class="container">
        <div class="topbar-content">
            <!-- Left side - Category Links -->
            <div class="topbar-left">
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Mobile phone') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Accessories') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Electronics') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Jobs') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Services') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Real Estate') }}</a>
                </div>
                <div class="topbar-item">
                    <a href="#" class="topbar-link">{{ ('Vehicles') }}</a>
                </div>
            </div>

            <!-- Right side - Icons -->
            <div class="topbar-right">
                <div class="topbar-icons">
                    <a href="{{ url('account/my-posts') }}" class="topbar-icon-link" title="{{ ('my_ads') }}">
                        <i class="far fa-list-alt"></i>
                    </a>
                    <a href="{{ url('account/favourite') }}" class="topbar-icon-link" title="{{ ('favourite_ads') }}">
                        <i class="far fa-heart"></i>
                    </a>
                    <a href="{{ url('account/messages') }}" class="topbar-icon-link" title="{{ ('messenger') }}">
                        <i class="far fa-comments"></i>
                    </a>
                    <!-- Add to Cart Icon -->
                    <button class="topbar-icon-link topbar-cart-icon" id="topbarCartButton" title="{{ ('Shopping Cart') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span id="topbarCartCount" class="topbar-cart-badge">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let topbarCartCountElement = document.getElementById("topbarCartCount");
    let topbarCartButton = document.getElementById("topbarCartButton");

    function updateTopbarCartCount() {
        $.ajax({
            url: "{{ route('cart.count') }}",
            method: "GET",
            success: function (response) {
                if (response.count > 0) {
                    topbarCartCountElement.textContent = response.count;
                    topbarCartCountElement.style.display = "flex";
                } else {
                    topbarCartCountElement.style.display = "none";
                }
            },
            error: function () {
                console.error("Failed to fetch cart count.");
            }
        });
    }

    updateTopbarCartCount();

    topbarCartButton.addEventListener("click", function () {
        let isAuthenticated = @json(auth()->check());
        if (!isAuthenticated) {
            $('#quickLogin').modal('show');
        } else {
            window.location.href = "{{ url('cart') }}";
        }
    });

    setInterval(updateTopbarCartCount, 30000);
});
</script>