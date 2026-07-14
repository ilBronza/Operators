@php
	$order = $orderrow->getOrder() ?: $orderrow->getModelContainer();
	$supplier = $orderrow->getSupplier();
	$supplierTarget = $supplier?->getTarget();
	$sellable = $orderrow->getSellable();
	$sellableElement = $sellable?->getTarget() ?: $sellable;
	$operator = $orderrow->getOperator() ?: $supplierTarget;
	$period = collect([
		$orderrow->getStartsAt()?->format('d/m/Y'),
		$orderrow->getEndsAt()?->format('d/m/Y'),
	])->filter()->implode(' - ');
@endphp

<div class="uk-padding-small">
	<div class="uk-flex uk-flex-between uk-flex-middle uk-margin-small-bottom">
		<div>
			<h3 class="uk-margin-remove">{{ $operator?->getName() ?: '-' }}</h3>
			<div class="uk-text-meta">{{ $sellableElement?->getName() ?: '-' }}</div>
		</div>

		@if($period)
			<span class="uk-label">{{ $period }}</span>
		@endif
	</div>

	<div class="uk-margin-small-bottom">
		@if($url = $order?->getEditUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Apri commessa</a>
		@endif

		@if($url = $orderrow->getEditUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Apri riga</a>
		@endif

		@if($url = $operator?->getShowUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Apri operatore</a>
		@endif

		@if($url = $order?->getGanttUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Gantt commessa</a>
		@endif

		@if($url = $sellableElement?->getGanttUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Gantt sellable</a>
		@endif

		@if($url = $operator?->getGanttUrl())
			<a class="uk-button uk-button-default uk-button-small uk-margin-small-right" href="{{ $url }}">Gantt operatore</a>
		@endif
	</div>

	<div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@m" uk-grid>
		<div>
			<div class="uk-card uk-card-default uk-card-small">
				<div class="uk-card-header">
					<h4 class="uk-card-title uk-margin-remove">Riga operatore</h4>
				</div>

				<div class="uk-card-body">
					<table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
						<tbody>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Mansione</th>
								<td>{{ $sellableElement?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Operatore</th>
								<td>{{ $operator?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Fornitore</th>
								<td>{{ ($supplierTarget ?: $supplier)?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Periodo</th>
								<td>{{ $period ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Quantita</th>
								<td>{{ filled($orderrow->getQuantity()) ? $orderrow->getQuantity() : '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Descrizione</th>
								<td>{{ filled($orderrow->getDescription()) ? $orderrow->getDescription() : '-' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div>
			<div class="uk-card uk-card-default uk-card-small">
				<div class="uk-card-header">
					<h4 class="uk-card-title uk-margin-remove">Commessa</h4>
				</div>

				<div class="uk-card-body">
					<table class="uk-table uk-table-small uk-table-divider uk-margin-remove">
						<tbody>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Commessa</th>
								<td>{{ $order?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Cliente</th>
								<td>{{ $order?->getClient()?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Progetto</th>
								<td>{{ $order?->getProject()?->getName() ?: '-' }}</td>
							</tr>
							<tr>
								<th class="uk-text-nowrap uk-width-small">Destinazione</th>
								<td>{{ $order?->getDestination()?->getName() ?: '-' }}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
