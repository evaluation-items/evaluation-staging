@props(['file', 'schemeId'])
@if(empty($file))
    <span>No File</span>
@else
    @php $ext = pathinfo($file, PATHINFO_EXTENSION); @endphp

    @if($ext === 'pdf')
        <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($schemeId), $file]) }}"
           target="_blank"
           title="{{ $file }}">
            <i class="fas fa-file-pdf fa-2x text-danger"></i>
        </a>
    @else
        <a href="{{ route('schemes.get_the_file', [Crypt::encrypt($schemeId), $file]) }}"
           download="{{ $file }}">
            <i class="fas fa-download fa-2x text-primary"></i>
        </a>
    @endif
@endif
