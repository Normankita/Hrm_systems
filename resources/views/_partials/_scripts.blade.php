 <script>
     // Loop through each input field
     const inputs = document.querySelectorAll('input[data-format="number"]');
     // format all first
     inputs.forEach(input => commaSeparator(input));

     // Loop through each input field
     inputs.forEach(input => {
         // implelement comma sepearator
         input.addEventListener('input', (event) => commaSeparator(event.target));
     });

     // global comma sepearator function
     function commaSeparator(input) {

         // Remove all non-digit characters except periods
         let value = input.value.replace(/,/g, '').replace(/[^\d.]/g, '');

         // Split decimal part if any
         let parts = value.split('.');
         parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

         // Combine parts
         input.value = parts.join('.');
     };
 </script>
 <script>
     document.addEventListener("DOMContentLoaded", function() {

         const trtButtons = document.querySelectorAll('button.trt');

         trtButtons.forEach(function(button) {
             button.addEventListener('click', function(event) {

                 // Allow natural form submit
                 const form = button.closest('form');

                 // Disable AFTER submit begins
                 setTimeout(() => {
                     button.disabled = true;
                 }, 0);
             });
         });

     });
 </script>

 <script src="{{ asset('bootstrap5.1.1/plugins/jquery/jquery.min.js') }}"></script>
 <script src="{{ asset('bootstrap5.1.1/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 <script src="{{ asset('bootstrap5.1.1/plugins/simplebar/simplebar.min.js') }}"></script>

 <script src="{{ asset('bootstrap5.1.1/plugins/prism/prism.js') }}"></script>
 {{-- <script src="{{ asset('bootstrap5.1.1/js/mono.js') }}"></script> --}}
 <script src="{{ asset('bootstrap5.1.1/js/chart.js') }}"></script>
 <script src="{{ asset('bootstrap5.1.1/js/map.js') }}"></script>
 <script src="{{ asset('bootstrap5.1.1/js/custom.js') }}"></script>
 {{-- <script src="{{ asset('bootstrap5.1.1/plugins/nprogress/nprogress.js') }}"></script> --}}
 <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

 <script src="{{ asset('bootstrap5.1.1/js/defaultTableData.js') }}"></script>

 <script src="{{ asset('bootstrap5.1.1/js/input-selector.js') }}"></script>

 <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

 <script>
     $(document).ready(function() {
         printerButtons = $('.printers-buttons');
         printerButtons.removeClass('d-none');
     });
 </script>
