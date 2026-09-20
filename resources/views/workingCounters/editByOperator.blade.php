@extends('uikittemplate::app')

@section('content')

<h2 class="uk-margin-remove-top">
	@lang('operators::fields.workingCounters') — {{ $operator->getName() }}
</h2>

@if($workingCounters->isEmpty())
	<p>@lang('operators::workingCounters.noCounters')</p>
@else
	<form method="POST" action="{{ app('operators')->route('workingCounters.updateByOperator', ['operator' => $operator]) }}" class="uk-form-stacked">
		@csrf
		@method('PUT')

		<div class="uk-overflow-auto">
			<table class="uk-table uk-table-small uk-table-divider">
				<thead>
					<tr>
						<th>@lang('operators::fields.type')</th>
						<th>@lang('operators::fields.amount')</th>
						<th>@lang('operators::fields.reset_date')</th>
						{{-- <th>@lang('operators::fields.expired_at')</th> --}}
					</tr>
				</thead>
				<tbody>
					@foreach($workingCounters as $index => $workingCounter)
						@php($inputName = 'workingCounters.' . $index)
						<tr>
							<td>
								{{ $workingCounter->getTypeLabel() }}
								<input type="hidden" name="workingCounters[{{ $index }}][id]" value="{{ $workingCounter->getKey() }}">
							</td>
							<td>
								<input
									class="uk-input @error($inputName . '.amount') uk-form-danger @enderror"
									type="number"
									name="workingCounters[{{ $index }}][amount]"
									step="0.01"
									required
									value="{{ old($inputName . '.amount', $workingCounter->amount) }}"
								>
								@error($inputName . '.amount')
									<div class="uk-text-danger uk-text-small">{{ $message }}</div>
								@enderror
							</td>
							<td>
								<input
									class="uk-input @error($inputName . '.reset_date') uk-form-danger @enderror"
									type="date"
									name="workingCounters[{{ $index }}][reset_date]"
									required
									value="{{ old($inputName . '.reset_date', $workingCounter->reset_date?->format('Y-m-d')) }}"
								>
								@error($inputName . '.reset_date')
									<div class="uk-text-danger uk-text-small">{{ $message }}</div>
								@enderror
							</td>
{{-- 							<td>
								<input
									class="uk-input @error($inputName . '.expired_at') uk-form-danger @enderror"
									type="datetime-local"
									name="workingCounters[{{ $index }}][expired_at]"
									value="{{ old($inputName . '.expired_at', $workingCounter->expired_at?->format('Y-m-d\\TH:i')) }}"
								>
								@error($inputName . '.expired_at')
									<div class="uk-text-danger uk-text-small">{{ $message }}</div>
								@enderror
							</td>
 --}}						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<div class="uk-margin-top uk-text-right">
			<button class="uk-button uk-button-primary" type="submit">
				@lang('operators::workingCounters.saveCounters')
			</button>
		</div>
	</form>
@endif

@endsection
