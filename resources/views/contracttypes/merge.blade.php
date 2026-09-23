@extends('uikittemplate::app')

@section('content')
	<div class="uk-card uk-card-default uk-card-body uk-margin-auto" style="max-width: 1100px;">
		<h1 class="uk-h3">Accorpa mansioni</h1>
		<p>Hai selezionato <strong>{{ $selected->count() }} mansioni</strong> con <strong>{{ $selectedOperatorCount }} operatori distinti</strong>. Scegli la mansione master da mantenere: le altre mansioni selezionate saranno archiviate e i loro operatori saranno associati anche al master.</p>

		@if ($errors->any())
			<div class="uk-alert-danger uk-padding-small" role="alert">
				@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
			</div>
		@endif

		<form id="contracttype-merge-form" method="POST" action="{{ app('operators')->route('contracttypes.merge.store') }}" class="uk-form-stacked">
			@csrf
			@foreach ($selected as $contracttype)
				<input type="hidden" name="ids[]" value="{{ $contracttype->getKey() }}">
			@endforeach

			<div class="uk-margin">
				<label class="uk-form-label" for="contracttype-master">Mansione master da mantenere</label>
				<select id="contracttype-master" name="master_id" class="uk-select" required aria-describedby="contracttype-master-help">
					<option value="">Scegli la mansione master</option>
					@foreach (['Mansioni selezionate' => true, 'Altre mansioni attive' => false] as $label => $inSelection)
						<optgroup label="{{ $label }}">
							@foreach ($candidates as $candidate)
								@if ($selected->contains('id', $candidate->getKey()) === $inSelection)
									<option value="{{ $candidate->getKey() }}" @selected(old('master_id') === $candidate->getKey())>
										{{ $candidate->getName() }} — {{ $operatorCounts->get($candidate->getKey(), 0) }} operatori{{ $candidate->istat_code ? ' — ISTAT ' . $candidate->istat_code : '' }}
										@if (in_array($candidate->name, $duplicateNames, true)) — rif. {{ substr($candidate->getKey(), 0, 8) }} @endif
									</option>
								@endif
							@endforeach
						</optgroup>
					@endforeach
				</select>
				<p id="contracttype-master-help" class="uk-text-meta">Puoi scegliere anche un master esterno alla selezione. Se hai selezionato una sola mansione, scegli un master diverso.</p>
			</div>

			<div class="uk-overflow-auto">
				<table class="uk-table uk-table-small uk-table-divider uk-table-middle">
					<caption>Mansioni selezionate</caption>
					<thead><tr><th>Mansione</th><th>Codice ISTAT</th><th>Operatori associati</th><th>Esito alla conferma</th></tr></thead>
					<tbody>
						@foreach ($selected as $contracttype)
							<tr data-contracttype-id="{{ $contracttype->getKey() }}">
								<td>
									<strong>{{ $contracttype->getName() }}</strong>
									@if (in_array($contracttype->name, $duplicateNames, true))<span class="uk-text-meta"> — rif. {{ substr($contracttype->getKey(), 0, 8) }}</span>@endif
									@if ($contracttype->description)<div class="uk-text-meta">{{ $contracttype->description }}</div>@endif
								</td>
								<td>{{ $contracttype->istat_code ?: '—' }}</td>
								<td>{{ $operatorCounts->get($contracttype->getKey(), 0) }}</td>
								<td data-merge-outcome>Scegli il master</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div id="contracttype-merge-summary" class="uk-alert-primary uk-padding-small uk-margin" aria-live="polite" hidden>
				<div class="uk-grid-small uk-child-width-1-2 uk-child-width-1-3@m" uk-grid>
					@foreach ([
						'slave_count' => 'Mansioni da archiviare',
						'slave_operators' => 'Operatori distinti delle slave',
						'master_operators' => 'Operatori attuali del master',
						'already_associated' => 'Delle slave, già sul master',
						'new_associations' => 'Operatori da aggiungere',
						'final_operators' => 'Totale finale sul master',
					] as $key => $label)
						<div><span class="uk-h3" data-merge-count="{{ $key }}">0</span><br>{{ $label }}</div>
					@endforeach
				</div>
			</div>
			<p id="contracttype-merge-empty" class="uk-text-danger" role="alert" hidden>Non ci sono mansioni slave da archiviare. Scegli un master diverso.</p>

			<p>Le associazioni alle vecchie mansioni e lo storico di contratti, ordini e preventivi vengono conservati. Gli operatori già presenti sul master mantengono le loro tariffe; per le nuove associazioni non vengono copiate le tariffe delle slave.</p>
			<p class="uk-text-meta">Sono inclusi anche gli operatori inattivi. I conteggi escludono operatori e associazioni eliminati e vengono ricalcolati alla conferma.</p>

			<div class="uk-margin">
				<label><input id="contracttype-merge-confirm" class="uk-checkbox" type="checkbox" name="confirm" value="1" required> Confermo l’associazione degli operatori al master e l’archiviazione delle mansioni slave indicate.</label>
			</div>
			<div class="uk-flex uk-flex-wrap uk-flex-middle uk-grid-small" uk-grid>
				<div><button id="contracttype-merge-submit" class="uk-button uk-button-primary" type="submit" disabled>Conferma accorpamento</button></div>
				<div><a class="uk-button uk-button-default" href="{{ \IlBronza\Operators\Models\Contracttype::gpc()::make()->getIndexUrl() }}">Annulla</a></div>
			</div>
		</form>
	</div>
	<script>
		(() => {
			const summaries = @json($summaries);
			const form = document.getElementById('contracttype-merge-form');
			const master = document.getElementById('contracttype-master');
			const confirmation = document.getElementById('contracttype-merge-confirm');
			const submit = document.getElementById('contracttype-merge-submit');

			function updateSummary() {
				const summary = summaries[master.value];
				document.getElementById('contracttype-merge-summary').hidden = !summary;
				document.getElementById('contracttype-merge-empty').hidden = !summary || summary.slave_count > 0;
				form.querySelectorAll('[data-merge-count]').forEach(element => {
					element.textContent = summary ? summary[element.dataset.mergeCount] : '0';
				});
				form.querySelectorAll('[data-contracttype-id]').forEach(row => {
					const isMaster = row.dataset.contracttypeId === master.value;
					const outcome = row.querySelector('[data-merge-outcome]');
					outcome.textContent = !summary ? 'Scegli il master' : (isMaster ? 'Master — mantenuta' : 'Slave — da archiviare');
					outcome.className = !summary ? '' : (isMaster ? 'uk-text-success' : 'uk-text-warning');
				});
				submit.disabled = !summary || summary.slave_count === 0 || !confirmation.checked;
			}

			master.addEventListener('change', () => {
				confirmation.checked = false;
				updateSummary();
			});
			confirmation.addEventListener('change', updateSummary);
			form.addEventListener('submit', () => { submit.disabled = true; });
			window.addEventListener('pageshow', updateSummary);
			updateSummary();
		})();
	</script>
@endsection
