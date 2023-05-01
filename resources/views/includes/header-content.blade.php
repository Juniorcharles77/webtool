@if($generalSettings->customStyles)
<style>
    {!! $generalSettings->customStyles !!}
</style>
@endif

@if(isset($styles) && $styles)
    @foreach($styles as $style)
        <link rel="stylesheet" href="{{ $style[1] == 'internal' ? asset($style[0]) : $style[0] }}">
    @endforeach
@endif

@foreach($generalSettings->styles as $style)
    <link rel="stylesheet" href="{{ $style['url'] }}">
@endforeach


@foreach($generalSettings->scripts as $script)
    @if($script['location'] == 'header')
        <script src="{{ $script['url'] }}"></script>
    @endif
@endforeach

@if($generalSettings->gaId)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $generalSettings->gaId }}" type="text/javascript"></script>
<script type="text/javascript">
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '{{ $generalSettings->gaId }}');
</script>
@endif

<script>
    window.bitflanBaseUrl = '{{ url("/") }}';
    window.copiedIntlString = `{{ trans('webtools/general.copied') }}`;
</script>

@if($adSettings->popAdStatus && $adSettings->popAdLocation == 'header')
    {!! $adSettings->popAdCode !!}
@endif

@if($generalSettings->headerTags)
    {!! $generalSettings->headerTags !!}
@endif