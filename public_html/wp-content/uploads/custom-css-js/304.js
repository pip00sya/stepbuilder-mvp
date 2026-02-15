<!-- start Simple Custom CSS and JS -->
<script type="text/javascript">
jQuery(document).ready(function($) {
  // Только на странице 280
  if (window.location.pathname.includes("/?page_id=280") || window.location.href.includes("page_id=280")) {
    $('.acf-field input, .acf-field textarea, .acf-field select').on('blur change', function () {
      let fieldName = $(this).attr('name');
      let fieldValue = $(this).val();

      $.post(ajaxurl, {
        action: 'autosave_acf_field',
        field: fieldName,
        value: fieldValue
      }, function (response) {
        console.log(response.data || response);
      });
    });
  }
});</script>
<!-- end Simple Custom CSS and JS -->
