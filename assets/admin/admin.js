(function () {
  "use strict";

  function ready(fn) {
    if (document.readyState !== "loading") {
      fn();
    } else {
      document.addEventListener("DOMContentLoaded", fn);
    }
  }

  ready(function () {
    const wrapper = document.getElementById("sfa-faq-wrapper");
    const addBtn = document.getElementById("sfa-add-faq");
    const templateEl = document.getElementById("sfa-faq-row-template");

    if (!wrapper || !addBtn || !templateEl) return;

    function getNextIndex() {
      // Count existing rows to determine next index.
      return wrapper.querySelectorAll(".sfa-faq-row").length;
    }

    function addRow() {
      const index = getNextIndex();
      let html = templateEl.innerHTML.replaceAll("{INDEX}", String(index));

      const tempDiv = document.createElement("div");
      tempDiv.innerHTML = html.trim();

      const row = tempDiv.firstElementChild;
      if (row) wrapper.appendChild(row);
    }

    function removeRow(buttonEl) {
      const row = buttonEl.closest(".sfa-faq-row");
      if (!row) return;

      const rows = wrapper.querySelectorAll(".sfa-faq-row");
      if (rows.length <= 1) {
        // Keep at least one row for UX.
        const q = row.querySelector('input[type="text"]');
        const a = row.querySelector("textarea");
        if (q) q.value = "";
        if (a) a.value = "";
        return;
      }

      row.remove();
    }

    addBtn.addEventListener("click", function () {
      addRow();
    });

    wrapper.addEventListener("click", function (e) {
      const target = e.target;
      if (!target) return;

      if (target.classList.contains("sfa-remove-faq")) {
        e.preventDefault();
        removeRow(target);
      }
    });

    function wrapSelection(textarea, before, after) {
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      const value = textarea.value;

      const selected = value.substring(start, end);
      const newText = before + selected + after;

      textarea.setRangeText(newText, start, end, "end");
      textarea.focus();
    }

    function insertLink(textarea) {
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      const value = textarea.value;
      const selected = value.substring(start, end) || "link text";

      const url = window.prompt("Enter URL (https://...):");
      if (!url) return;

      const linkHtml = `<a href="${url}" target="_blank" rel="noopener">${selected}</a>`;
      textarea.setRangeText(linkHtml, start, end, "end");
      textarea.focus();
    }

    wrapper.addEventListener("click", function (e) {
      const btn = e.target.closest(".sfa-format-btn");
      if (!btn) return;

      e.preventDefault();

      const row = btn.closest(".sfa-faq-row");
      if (!row) return;

      const textarea = row.querySelector("textarea");
      if (!textarea) return;

      const cmd = btn.getAttribute("data-cmd");

      if (cmd === "bold") wrapSelection(textarea, "<strong>", "</strong>");
      if (cmd === "italic") wrapSelection(textarea, "<em>", "</em>");
      if (cmd === "underline") wrapSelection(textarea, "<u>", "</u>");
      if (cmd === "link") insertLink(textarea);
    });
  });
})();
