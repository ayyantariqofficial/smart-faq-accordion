(function () {
  "use strict";

  function initAccordion(root) {
    const singleOpen = root.getAttribute("data-single-open") === "1";
    const openFirst = root.getAttribute("data-open-first") === "1";

    const items = Array.from(root.querySelectorAll(".sfa-item"));
    if (!items.length) return;

    function closeItem(item) {
      const btn = item.querySelector(".sfa-question");
      const panel = item.querySelector(".sfa-answer");
      if (!btn || !panel) return;

      btn.setAttribute("aria-expanded", "false");
      panel.hidden = true;
      item.classList.remove("is-open");
    }

    function openItem(item) {
      const btn = item.querySelector(".sfa-question");
      const panel = item.querySelector(".sfa-answer");
      if (!btn || !panel) return;

      if (singleOpen) {
        items.forEach((it) => {
          if (it !== item) closeItem(it);
        });
      }

      btn.setAttribute("aria-expanded", "true");
      panel.hidden = false;
      item.classList.add("is-open");
    }

    function toggleItem(item) {
      const btn = item.querySelector(".sfa-question");
      if (!btn) return;

      const expanded = btn.getAttribute("aria-expanded") === "true";
      if (expanded) {
        closeItem(item);
      } else {
        openItem(item);
      }
    }

    // Click handlers
    items.forEach((item) => {
      const btn = item.querySelector(".sfa-question");
      if (!btn) return;

      btn.addEventListener("click", function () {
        toggleItem(item);
      });
    });

    // Open first item if setting enabled
    if (openFirst) {
      openItem(items[0]);
    }
  }

  function ready(fn) {
    if (document.readyState !== "loading") fn();
    else document.addEventListener("DOMContentLoaded", fn);
  }

  ready(function () {
    document.querySelectorAll(".sfa-accordion").forEach(initAccordion);
  });
})();
