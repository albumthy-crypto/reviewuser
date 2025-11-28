
(function (window, $) {
  "use strict";

  // Expose a module for import modal interactions
  var ImportModal = (function () {
    var $overlay = $("#importDrawerOverlay");
    var $modal = $("#importDrawer");
    var $close = $("#importDrawerClose");
    var $cancel = $("#drawerCancelBtn");
    var $upload = $("#drawerUploadBtn");
    var $fileInput = $("#drawerFileInput");
    var $fileChoose = $("#fileChooseBtn");
    var $fileName = $(".file-name");
    var $fileProgress = $(".file-progress");
    var $progressFill = $(".progress-fill");
    var currentXhr = null;

    // Period list elements
    var $periodList = $("#periodList");
    var $periodItems = null;

    function open() {
      $overlay.removeClass("hidden").attr("aria-hidden", "false");
      $modal.removeClass("hidden").attr("aria-hidden", "false");
      // refresh item references
      $periodItems = $periodList.find(".period-item");
    }
    function close() {
      $overlay.addClass("hidden").attr("aria-hidden", "true");
      $modal.addClass("hidden").attr("aria-hidden", "true");
      reset();
    }
    function reset() {
      $fileInput.val("");
      $fileName.text("No file selected");
      $fileProgress.text("0%");
      $progressFill.css("width", "0%");
    }

    function focusItem($item) {
      $periodItems.attr("tabindex", "-1").attr("aria-selected", "false");
      $item.attr("tabindex", "0").attr("aria-selected", "true").focus();
    }

    function selectItem($item) {
      $periodItems.removeClass("active").attr("aria-selected", "false");
      $item.addClass("active").attr("aria-selected", "true");
      // update selected info UI
      var title = $item.find("span").first().text();
      var date = $item.find(".period-date").text();
      $(".selected-title").text(title);
      $(".selected-date").text(date);
    }

    // small toast helper
    function showToast(message, type) {
      type = type || "success";
      var $t = $(
        '<div class="app-toast ' +
          type +
          '" role="status" aria-live="polite">' +
          message +
          "</div>"
      );
      $("body").append($t);
      setTimeout(function () {
        $t.addClass("visible");
      }, 20);
      setTimeout(function () {
        $t.removeClass("visible");
        setTimeout(function () {
          $t.remove();
        }, 300);
      }, 3500);
    }

    function bind() {
      // Openers (some pages may call this externally)
      $(document).on("click", "[data-open-import]", function (e) {
        e.preventDefault();
        open();
      });

      // Closeers
      $close.on("click", function (e) {
        e.preventDefault();
        close();
      });
      $overlay.on("click", function (e) {
        e.preventDefault();
        close();
      });
      $cancel.on("click", function (e) {
        e.preventDefault();
        close();
      });

      // File chooser
      $fileChoose.on("click", function (e) {
        e.preventDefault();
        $fileInput.trigger("click");
      });
      $fileInput.on("change", function () {
        var f = this.files && this.files[0];
        var $fileError = $("#fileError");
        $fileError.hide().text("");
        if (!f) return reset();

        // Client-side validation
        var allowed = ["csv", "xls", "xlsx"];
        var ext = (f.name.split(".").pop() || "").toLowerCase();
        var maxBytes = 10 * 1024 * 1024; // 10 MB
        if (allowed.indexOf(ext) === -1) {
          $fileError
            .text("Invalid file type. Allowed: " + allowed.join(", "))
            .show();
          $fileInput.val("");
          reset();
          return;
        }
        if (f.size > maxBytes) {
          $fileError.text("File is too large. Max: 10 MB").show();
          $fileInput.val("");
          reset();
          return;
        }

        $fileName.text(f.name);
        $fileProgress.text("0%");
        $progressFill.css("width", "0%");
      });

      // period list click
      $periodList.on("click", ".period-item", function () {
        var $t = $(this);
        selectItem($t);
      });

      // keyboard navigation for period list
      $periodList.on("keydown", function (e) {
        var $focused = $periodList.find(".period-item:focus");
        if (!$focused.length) {
          // focus the active or first
          var $start = $periodList.find(".period-item.active");
          if (!$start.length) $start = $periodList.find(".period-item").first();
          focusItem($start);
          return;
        }
        if (e.key === "ArrowDown" || e.key === "Down") {
          e.preventDefault();
          var $next = $focused.nextAll(".period-item").first();
          if ($next.length) focusItem($next);
        } else if (e.key === "ArrowUp" || e.key === "Up") {
          e.preventDefault();
          var $prev = $focused.prevAll(".period-item").first();
          if ($prev.length) focusItem($prev);
        } else if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          selectItem($focused);
        }
      });

      // Upload with progress and abort support
      $upload.on("click", function (e) {
        e.preventDefault();
        var f = $fileInput[0] && $fileInput[0].files && $fileInput[0].files[0];
        var $fileError = $("#fileError");
        $fileError.hide().text("");
        if (!f) {
          $fileError.text("Please select a file to upload").show();
          return;
        }

        var form = new FormData();
        form.append("employee_file", f);

        $upload.prop("disabled", true).text("Uploading...");

        currentXhr = $.ajax({
          url: "import_employee.php",
          method: "POST",
          data: form,
          processData: false,
          contentType: false,
          dataType: "json",
          xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                var p = Math.round((evt.loaded / evt.total) * 100);
                $fileProgress.text(p + "%");
                $progressFill.css("width", p + "%");
              }
            });
            return xhr;
          },
        })
          .done(function (resp) {
            if (resp && resp.success) {
              $fileProgress.text("100%");
              $progressFill.css("width", "100%");
              showToast(
                "Imported " + (resp.inserted || 0) + " rows",
                "success"
              );
              close();
              setTimeout(function () {
                location.reload();
              }, 700);
            } else {
              var msg = resp && resp.message ? resp.message : "Import failed";
              $("#fileError").text(msg).show();
            }
          })
          .fail(function (jqXHR) {
            var text = "Upload failed: " + (jqXHR.status || "network");
            try {
              var json =
                jqXHR.responseJSON ||
                (jqXHR.responseText && JSON.parse(jqXHR.responseText));
              if (json && json.message) text = json.message;
            } catch (e) {}
            $("#fileError").text(text).show();
          })
          .always(function () {
            $upload.prop("disabled", false).text("Upload");
            currentXhr = null;
          });
      });
    }

    return { open: open, close: close, bind: bind };
  })();

  // Auto bind on DOM ready
  $(function () {
    ImportModal.bind();
    window.ImportModal = ImportModal;
  });
})(window, jQuery);
