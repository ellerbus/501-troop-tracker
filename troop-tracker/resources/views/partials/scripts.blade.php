<!-- JQUERY -->
<script src="script/lib/jquery-3.4.1.min.js"></script>

<!-- JQUERY UI -->
<script src="script/lib/jquery-ui.min.js"></script>

<!-- JQUERY SELECT -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Addons -->
<script src="script/lib/jquery-ui-timepicker-addon.js"></script>
<script src="script/js/validate/jquery.validate.min.js"></script>
<script src="script/js/validate/additional-methods.min.js"></script>
<script src="script/js/validate/validate.js?v=4"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script
        src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<!-- Drop Zone -->
<script src="script/lib/dropzone.min.js"></script>

<!-- LightBox -->
<script src="script/lib/lightbox.min.js"></script>

<!-- HTMX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/htmx/2.0.7/htmx.min.js"></script>

<script>
  document.addEventListener('htmx:configRequest', function (event) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    event.detail.headers['X-CSRF-TOKEN'] = token;
  });
</script>

<script>
  $(document).ready(function () {
    // Add rules to clubs - IDs
    $('#rebelforum').each(function () {
      $(this).rules('add',
        {
          digits: false,
          required: function () {
            return $('#squad').val() == 6;
          }
        })
    });
    // Add rules to clubs - IDs
    $('#mandoid').each(function () {
      $(this).rules('add',
        {
          digits: true,
          required: function () {
            return $('#squad').val() == 8;
          }
        })
    });
    // Add rules to clubs - IDs
    $('#sgid').each(function () {
      $(this).rules('add',
        {
          digits: true,
          required: false
        })
    });
    // Add rules to clubs - IDs
    $('#de_id').each(function () {
      $(this).rules('add',
        {
          digits: true,
          required: false
        })
    });
  });
</script>
<!-- External JS File -->
<script type="text/javascript"
        src="script/js/main.js?v=9"></script>