@if($image)
	<img src="{{ $image }}" alt="Avatar">
@else
	<img src="{{ config('operators.missingImageUrl', ) }}" alt="Avatar">
@endif