@foreach($lines as $line)
    {!! strip_tags($line) !!}
@endforeach
@if(!empty($comments))
    {!! strip_tags($comments) !!}
@endif
