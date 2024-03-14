<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1280">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ \Laravel\Nova\Nova::name() }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('app.css', 'vendor/nova') }}">

    <!-- Tool Styles -->
    @foreach(\Laravel\Nova\Nova::availableStyles(request()) as $name => $path)
        <link rel="stylesheet" href="/nova-api/styles/{{ $name }}">
    @endforeach

    <!-- Custom Meta Data -->
    @include('nova::partials.meta')

    <!-- Theme Styles -->
    @foreach(\Laravel\Nova\Nova::themeStyles() as $publicPath)
        <link rel="stylesheet" href="{{ $publicPath }}">
    @endforeach

    <!-- CSS images -->
    <link rel="preload" as="image" href="/storage/remove.svg">
    <link rel="preload" as="image" href="/storage/remove-light.svg">    
    <link rel="preload" as="image" href="/storage/edit.svg">
    <link rel="preload" as="image" href="/storage/edit-light.svg">
    <link rel="preload" as="image" href="/storage/arrow-left.svg">
    <link rel="preload" as="image" href="/storage/arrow-right.svg">
    <link rel="preload" as="image" href="/storage/checked.svg">
    <link rel="preload" as="image" href="/storage/unchecked.svg">    
    <link rel="preload" as="image" href="/storage/filter-selected.svg">
    <link rel="preload" as="image" href="/storage/filter.svg">
    <link rel="preload" as="image" href="/storage/not-found.svg">
    <link rel="preload" as="image" href="/storage/sort.svg">

</head>
<body {{--class="min-w-site bg-40 text-black min-h-full"--}}>
    <div id="nova">
        <div v-cloak class="flex main-container" {{--class="flex min-h-screen"--}} 
        style="{{ \Kovrigo\Apply\Env::sideBarStyle() }}">
            <!-- Sidebar -->
            {{--
            <div class="min-h-screen flex-none pt-header min-h-screen w-sidebar bg-grad-sidebar px-6">
                <a href="\">
                    <div class="absolute pin-t pin-l pin-r bg-logo flex items-center w-sidebar h-header px-6 text-white">
                       @include('nova::partials.logo')
                    </div>
                </a>
                @foreach (\Laravel\Nova\Nova::availableTools(request()) as $tool)
                    {!! $tool->renderNavigation() !!}
                @endforeach
            </div>--}}
            <div class="sidebar">
                <div class="logo">
                    <a href="{{ optional(apply()->settings)->home }}">
                        <img src="/storage/sidebar-logo.svg">
                    </a>
                </div>
                <custom-navigation 
                    navigation-json="{{ json_encode(optional(apply()->settings)->navigation) }}">
                </custom-navigation>
            </div>

            <!-- Content -->
            <div class="content">
                <div class="flex items-center relative z-20 topbar">
                    {{--<a v-if="@json(\Laravel\Nova\Nova::name() !== null)" href="{{ \Illuminate\Support\Facades\Config::get('nova.url') }}" class="no-underline dim font-bold text-90 mr-6">
                        {{ \Laravel\Nova\Nova::name() }}
                    </a>--}}

                    @if (count(\Laravel\Nova\Nova::globallySearchableResources(request())) > 0)
                        <global-search dusk="global-search-component"></global-search>
                    @endif

                    {{-- Balance --}}
                    @can('viewAny', \App\Transaction::class)
                        <div class="ml-auto h-9 flex items-center balance">
                            @if (!apply()->isHost)
                                <div>
                                    <span class="balance-label">баланс:</span>
                                    <span class="balance-value">{{ Auth::user()->tenant->balance }}</span>
                                    <span class="balance-currency">{{ Auth::user()->tenant->currency->name }}</span>
                                </div>
                            @endif
                        </div>
                        <dropdown class="ml-4 h-9 flex items-center dropdown-right">
                    @endcan
                    @cannot('viewAny', \App\Transaction::class)
                        <dropdown class="ml-auto h-9 flex items-center dropdown-right">
                    @endcannot                        
                            @include('nova::partials.user')
                        </dropdown>

                    {{-- Locale select dropdown --}}
                    <dropdown class="ml-4 h-9 flex items-center dropdown-right">
                        <dropdown-trigger class="h-9 flex items-center">
                            {{-- Empty div with the same class as gravatar to match heights --}}
                            <div class="h-8"></div>
                            <span class="text-90">
                                {{ App::getLocale() }}
                            </span>
                        </dropdown-trigger>
                        <dropdown-menu slot="menu" width="200" direction="rtl">
                            <ul class="list-reset">
                                @foreach (Config::get('translatable.locales') as $localeCode => $localeName)
                                    @if ($localeCode != App::getLocale())
                                        <li>
                                            <a href="{{ '/lang/' . $localeCode }}" class="block no-underline text-90 hover:bg-30 p-3">{{ $localeName }} ({{ $localeCode }})</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </dropdown-menu>
                    </dropdown>

                </div>

                <div data-testid="content" class="px-view py-view mx-auto">
                    @yield('content')

                    {{--@include('nova::partials.footer')--}}
                </div>
            </div>
        </div>
    </div>

    <script>
        window.config = @json(\Laravel\Nova\Nova::jsonVariables(request()));
    </script>

    <!-- Scripts -->
    <script src="{{ mix('manifest.js', 'vendor/nova') }}"></script>
    <script src="{{ mix('vendor.js', 'vendor/nova') }}"></script>
    <script src="{{ mix('app.js', 'vendor/nova') }}"></script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{config('google-autocomplete.api_key')}}&libraries=places&language={{ str_replace('_', '-', app()->getLocale()) }}"></script>

    <!-- Build Nova Instance -->
    <script>
        window.Nova = new CreateNova(config)
    </script>

    <!-- Tool Scripts -->
    @foreach (\Laravel\Nova\Nova::availableScripts(request()) as $name => $path)
        @if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://']))
            <script src="{!! $path !!}"></script>
        @else
            <script src="/nova-api/scripts/{{ $name }}"></script>
        @endif
    @endforeach

    <!-- Start Nova -->
    <script>
        Nova.liftOff()
    </script>

</body>
</html>
