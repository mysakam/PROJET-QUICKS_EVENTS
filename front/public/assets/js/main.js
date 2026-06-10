document.addEventListener("DOMContentLoaded", function () {
	var toast = document.querySelector("[data-flash-toast]");

	if (toast) {
		window.setTimeout(function () {
			toast.classList.add("is-hiding");

			window.setTimeout(function () {
				toast.remove();
			}, 220);
		}, 1000);
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
});
