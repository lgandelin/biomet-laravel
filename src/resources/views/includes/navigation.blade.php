<div class="navigation">
    <a class="logo" href="{{ route('dashboard') }}"><img src="{{ asset('img/logo-arol-energy.png') }}" alt="Arol Energy" /></a>

    <div class="facilities-switcher">
        <select>
            <option value="">Choisir un site à consulter</option>
            @foreach ($navigation_facilities as $f)
                <option value="{{ $f->id }}" @if(isset($current_facility) && isset($current_facility->id) && $f->id === $current_facility->id)selected="selected"@endif>{{ $f->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="logout">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}<a href="{{ route('logout') }}"><i class="logout-icon"></i></a></div>
</div>

<script>
    @if (isset($current_facility) && $current_facility->id && preg_match('/facility/', $current_route))
        var route = "{{ route($current_route, ['id' => '@@@', 'tab' => $current_tab]) }}";
    @else
        var route = "{{ route('facility', ['id' => '@@@', 'tab' => 0]) }}";
    @endif

    $('.facilities-switcher select').change(function() {
        window.location.href = route.replace(/@@@/g,$(this).val())
    });
</script>