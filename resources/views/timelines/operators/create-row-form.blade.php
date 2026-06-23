<form
	class="uk-form-stacked timeline-create-row-form"
	method="POST"
	action="{{ $action }}"
	data-title="{{ $title }}"
>
	@csrf

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-starts-at">Inizio</label>
		<div class="uk-form-controls">
			<input
				id="timeline-create-starts-at"
				class="uk-input"
				type="datetime-local"
				name="starts_at"
				value="{{ $startsAt }}"
				required
			>
		</div>
	</div>

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-ends-at">Fine</label>
		<div class="uk-form-controls">
			<input
				id="timeline-create-ends-at"
				class="uk-input"
				type="datetime-local"
				name="ends_at"
				value="{{ $endsAt }}"
				required
			>
		</div>
	</div>

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-order">Commessa</label>
		<div class="uk-form-controls">
			<select id="timeline-create-order" class="uk-select" name="order_id" required>
				<option value="">Seleziona commessa</option>
				@foreach($possibleOrders as $order)
					<option value="{{ $order->getKey() }}">{{ $order->getName() }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-sellable">Sellable</label>
		<div class="uk-form-controls">
			<select id="timeline-create-sellable" class="uk-select" name="sellable_id" required>
				<option value="">Seleziona sellable</option>
				@foreach($possibleSellables as $sellable)
					<option value="{{ $sellable->getKey() }}">{{ $sellable->name }}</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-supplier">Fornitore</label>
		<div class="uk-form-controls">
			<select id="timeline-create-supplier" class="uk-select" name="supplier_id" required>
				<option value="">Seleziona fornitore</option>
				@foreach($possibleSuppliers as $supplier)
					<option
						value="{{ $supplier->getKey() }}"
						@selected($selectedSupplier?->is($supplier))
					>
						{{ $supplier->getName() }}
					</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="uk-margin">
		<label class="uk-form-label" for="timeline-create-operator">Operatore</label>
		<div class="uk-form-controls">
			<select id="timeline-create-operator" class="uk-select" name="operator_id">
				<option value="">Seleziona operatore</option>
				@foreach($possibleOperators as $operator)
					<option
						value="{{ $operator->getKey() }}"
						@selected($selectedOperator?->is($operator))
					>
						{{ $operator->getName() }}
					</option>
				@endforeach
			</select>
		</div>
	</div>

	<div class="uk-margin uk-text-right">
		<button type="submit" class="uk-button uk-button-primary">Salva</button>
	</div>
</form>
