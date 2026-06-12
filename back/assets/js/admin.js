document.addEventListener('DOMContentLoaded', function () {
	// Progressive enhancement: submit selected back-office forms via fetch.
	document.querySelectorAll('form[data-fetch-form]').forEach(function (form) {
		form.addEventListener('submit', function (event) {
			if ((form.method || 'get').toUpperCase() !== 'POST') {
				return;
			}

			if (form.querySelector('input[type="file"]')) {
				return;
			}

			event.preventDefault();

			var submitter = event.submitter;
			var submitterName = submitter && submitter.name ? submitter.name : '';
			var submitterValue = submitter && submitter.value ? submitter.value : '';
			var formData = new FormData(form);
			var csrfInput = form.querySelector('input[name="_csrf_token"]');
			var csrfToken = csrfInput ? csrfInput.value : '';
			if (submitterName && !formData.has(submitterName)) {
				formData.append(submitterName, submitterValue);
			}

			fetch(form.action, {
				method: 'POST',
				body: formData,
				headers: {
					'X-Requested-With': 'fetch',
					'X-CSRF-Token': csrfToken
				},
				credentials: 'same-origin'
			})
				.then(function (response) {
					if (response.redirected) {
						window.location.href = response.url;
						return null;
					}

					return response.text().then(function (html) {
						document.open();
						document.write(html);
						document.close();
						return null;
					});
				})
				.catch(function () {
					form.submit();
				});
		});
	});
});
