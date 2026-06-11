document.addEventListener("DOMContentLoaded", function () {
	var toast = document.querySelector("[data-flash-toast]");

	if (toast) {
		window.setTimeout(function () {
			toast.classList.add("is-hiding");

			window.setTimeout(function () {
				toast.remove();
			}, 220);
		}, 2000);
	}

	var toggle = document.querySelector(".menu-toggle");
	var nav = document.querySelector(".navbar");

	if (toggle && nav) {
		function closeMenu() {
			nav.classList.remove("is-open");
			document.body.classList.remove("nav-open");
			toggle.setAttribute("aria-expanded", "false");
		}

		toggle.addEventListener("click", function () {
			var willOpen = !nav.classList.contains("is-open");
			nav.classList.toggle("is-open", willOpen);
			document.body.classList.toggle("nav-open", willOpen);
			toggle.setAttribute("aria-expanded", willOpen ? "true" : "false");
		});

		nav.querySelectorAll("a").forEach(function (link) {
			link.addEventListener("click", function () {
				closeMenu();
			});
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape") {
				closeMenu();
			}
		});

		window.addEventListener("resize", function () {
			if (window.innerWidth > 1200) {
				closeMenu();
			}
		});
	}

	var lightbox = document.querySelector("[data-event-lightbox]");
	var lightboxImage = document.querySelector("[data-event-lightbox-image]");
	var imageTriggers = document.querySelectorAll("[data-package-image]");

	if (lightbox && lightboxImage && imageTriggers.length > 0) {
		function closeLightbox() {
			lightbox.setAttribute("hidden", "hidden");
			lightboxImage.setAttribute("src", "");
			lightboxImage.setAttribute("alt", "");
			document.body.classList.remove("lightbox-open");
		}

		imageTriggers.forEach(function (trigger) {
			trigger.addEventListener("click", function () {
				var src = trigger.getAttribute("data-image-src") || "";
				var alt = trigger.getAttribute("data-image-alt") || "";

				if (!src) {
					return;
				}

				lightboxImage.setAttribute("src", src);
				lightboxImage.setAttribute("alt", alt);
				lightbox.removeAttribute("hidden");
				document.body.classList.add("lightbox-open");
			});
		});

		lightbox.querySelectorAll("[data-event-lightbox-close]").forEach(function (closeBtn) {
			closeBtn.addEventListener("click", closeLightbox);
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape" && !lightbox.hasAttribute("hidden")) {
				closeLightbox();
			}
		});
	}
});
