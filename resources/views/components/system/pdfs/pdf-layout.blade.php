      @props(['id'])
      <!-- For PDF usability -->
      <div>
        <x-system.pdfs.pdf-trigger-btn :id="$id" />
          <div id="{{ $id }}" class="d-none">
              <div class="container">
                  <div class="col-md-12">
                      {{ $slot }}
                  </div>
              </div>
          </div>
      </div>


      <script>
          function downloadPDF(id) {
              var element = docment.getElementById(`${id}`);
              html2pdf().from(element).save();
          }
      </script>
