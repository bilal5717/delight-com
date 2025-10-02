{{-- Dynamic Automobiles Section --}}
@if (isset($posts) && $posts->count() > 0)
<div class="container">
    <div class="col-xl-12 content-box layout-section">
        <div class="row row-featured row-featured-category">
            
            <div class="col-xl-12 box-title no-border">
                <div class="inner">
                    <h2>
                        <span class="title-3">{{ $title ?? 'Featured Automobiles' }}</span>
                        <a href="{{ $link ?? '/automobiles' }}" class="sell-your-item">
                            View all automobiles <i class="icon-th-list"></i>
                        </a>
                    </h2>
                </div>
            </div>
            
            <div class="adds-wrapper noSideBar">
                <div class="row" style="display: flex; flex-wrap: wrap;">
                    
                    @foreach($posts as $post)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                        <div class="card h-100 position-relative border-0 shadow-sm">
                            
                            {{-- Featured Badge --}}
                            @if ($post->featured == 1)
                            <div class="position-absolute top-0 start-0 m-2">
                                <div class="badge" style="background-color: #007bff; color: white;">Featured</div>
                            </div>
                            @endif
                            
                            <div class="position-relative">
                                <div class="position-absolute top-0 end-0 m-2">
                                    @if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
                                        <a class="btn btn-success btn-sm make-favorite p-1" id="{{ $post->id }}">
                                            <i class="fa fa-heart fa-xs"></i>
                                        </a>
                                    @else
                                        <a class="btn btn-light btn-sm make-favorite p-1" id="{{ $post->id }}">
                                            <i class="fa fa-heart fa-xs"></i>
                                        </a>
                                    @endif
                                </div>
                                <a href="{{ \App\Helpers\UrlGen::post($post) }}">
                                    @if ($post->pictures->count() > 0)
                                        <img class="card-img-top w-100" src="{{ imgUrl($post->pictures->first()->filename, 'medium') }}" alt="{{ $post->title }}" style="height: 180px; object-fit: cover;">
                                    @else
                                        <img class="card-img-top w-100" src="{{ imgUrl(config('larapen.core.picture.default'), 'medium') }}" alt="{{ $post->title }}" style="height: 180px; object-fit: cover;">
                                    @endif
                                </a>
                            </div>
                            
                            <div class="card-body d-flex flex-column p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <a href="{{ \App\Helpers\UrlGen::post($post) }}" class="text-decoration-none">
                                            {{ \Illuminate\Support\Str::limit($post->title, 40) }}
                                        </a>
                                    </h6>
                                    <div class="text-end ms-2">
                                        <div class="fw-bold">
                                            @if (isset($post->category, $post->category->type))
                                                @if (!in_array($post->category->type, ['not-salable']))
                                                    @if (is_numeric($post->price) && $post->price > 0)
                                                        {!! \App\Helpers\Number::money($post->price) !!}
                                                    @elseif(is_numeric($post->price) && $post->price == 0)
                                                        {!! t('free_as_price') !!}
                                                    @else
                                                        {!! \App\Helpers\Number::money(' --') !!}
                                                    @endif
                                                @endif
                                            @else
                                                {{ '--' }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Automobile Specific Details --}}
                                @if (isset($post->car_model) || isset($post->year))
                                <div class="mb-1 small text-muted">
                                    <i class="fa fa-car me-1"></i>
                                    @if (isset($post->car_model))
                                        {{ $post->car_model }}
                                    @endif
                                    @if (isset($post->year))
                                        • {{ $post->year }}
                                    @endif
                                </div>
                                @endif
                                
                                <div class="mb-1 small text-muted">
                                    <i class="far fa-clock me-1"></i>
                                    @if (!config('settings.listing.hide_dates'))
                                        {!! $post->created_at_formatted !!}
                                    @endif
                                </div>
                                
                                <div class="small text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    @if ($post->city)
                                        <a href="{!! \App\Helpers\UrlGen::city($post->city) !!}" class="text-decoration-none">
                                            {{ $post->city->name }}
                                        </a>
                                        {{ (isset($post->distance)) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
                                    @else
                                        {{ t('Unknown') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
        
                <div class="clearfix"></div>
                
                <div class="mb-4 text-center mt-4">
                    <a href="{{ $link ?? '/automobiles' }}" class="btn btn-primary px-4">
                        <i class="fa fa-arrow-circle-right me-2"></i> View all automobiles
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endif