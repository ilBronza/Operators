<script>
	window.timelineCreateRowFormEndpoint = @json($timelineCreateRowFormEndpoint ?? null);

	(function () {
		const defaultCreateRowPopup = window.openTimelineCreateRowPopup;

		if (!window.timelineCreateRowFormEndpoint)
			return;

		window.openTimelineCreateRowPopup = async function (datetime, groupId)
		{
			const template = document.getElementById('timeline-item-modal-template');

			if (!template)
				return;

			const startDatetime = window.snapTimelineCreateDatetime(datetime);
			const endDatetime = window.getTimelineCreateEndDatetime(startDatetime);
			const formUrl = new URL(window.timelineCreateRowFormEndpoint, window.location.origin);

			formUrl.searchParams.set('starts_at', window.formatTimeInputValue(startDatetime));
			formUrl.searchParams.set('ends_at', window.formatTimeInputValue(endDatetime));

			if (groupId)
				formUrl.searchParams.set('group_id', groupId);

			const response = await fetch(formUrl.toString(), {
				headers: {
					'Accept': 'text/html',
				},
			});

			if (!response.ok)
			{
				if (typeof defaultCreateRowPopup === 'function')
					return defaultCreateRowPopup(datetime, groupId);

				throw new Error('HTTP ' + response.status);
			}

			const clone = template.firstElementChild.cloneNode(true);
			const modalId = 'timeline-create-modal-' + Date.now();
			clone.id = modalId;

			const modalTitleEl = clone.querySelector('.uk-modal-title');
			const modalContent = clone.querySelector('.timeline-modal-content');
			const footer = clone.querySelector('.uk-card-footer');

			if (footer)
				footer.remove();

			modalContent.innerHTML = await response.text();

			const wrapper = clone.querySelector('.timeline-create-row-form-wrapper');
			const form = clone.querySelector('.timeline-create-row-form') || clone.querySelector('form');

			if (!form)
				throw new Error('Form timeline non configurato');

			modalTitleEl.textContent = wrapper?.dataset.title || form.dataset.title || 'Nuova riga timeline';

			document.body.appendChild(clone);

			const modal = UIkit.modal('#' + modalId);
			modal.show();

			const startsAtInput = clone.querySelector('[name="starts_at"]');

			if (startsAtInput)
				setTimeout(function () { startsAtInput.focus(); }, 0);

			form.addEventListener('submit', async function (event)
			{
				event.preventDefault();

				const formData = new FormData(form);
				const payload = {};

				formData.forEach(function (value, key)
				{
					if (key !== '_token')
						payload[key] = value;
				});

				if (payload.starts_at)
					payload.starts_at = window.parseTimeInputValue(payload.starts_at).toISOString();

				if (payload.ends_at)
					payload.ends_at = window.parseTimeInputValue(payload.ends_at).toISOString();

				const saveButton = form.querySelector('button[type="submit"]');

				if (saveButton)
					saveButton.disabled = true;

				try
				{
					const headers = {
						'Content-Type': 'application/json',
						'Accept': 'application/json',
					};
					const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

					if (csrfToken)
						headers['X-CSRF-TOKEN'] = csrfToken;

					const saveResponse = await fetch(form.getAttribute('action'), {
						method: form.getAttribute('method') || 'POST',
						headers: headers,
						body: JSON.stringify(payload),
					});

					const data = await saveResponse.json();

					if (!saveResponse.ok || data.success !== true)
						throw new Error(data.message || ('HTTP ' + saveResponse.status));

					if (typeof window.addSuccessNotification === 'function')
						window.addSuccessNotification(data.message || 'Riga creata');

					modal.hide();
					await window.fetchTimeline();
				}
				catch (error)
				{
					if (typeof window.addDangerNotification === 'function')
						window.addDangerNotification(error.message || 'Errore durante il salvataggio');
				}
				finally
				{
					if (saveButton)
						saveButton.disabled = false;
				}
			});

			clone.addEventListener('hidden', function () {
				modal.$destroy(true);
				clone.remove();
			});
		};
	})();
</script>
