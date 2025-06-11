      @props(['id'])
      <!-- For PDF usability -->
      <div class="report" id="{{ $id }}">
          <!-- three buttons for pdf, excel, and print with MDI icons -->
          <div class="mb-3 d-none printers-buttons">
              <x-system.pdfs.pdf-trigger-btn :id="$id" />
              <x-system.excel.excel-trigger-btn :id="$id" />
          </div>
          <div>
              <div class="container">
                  <div class="col-md-12">
                      <!-- decrale a named slot called header -->
                      {{ $viewTable }}
                      <div class="d-none">
                          {{ $reportTable }}
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <script>
          function downloadPDF(id) {
              let element = document.getElementById(`${id}-pdf`);
              html2pdf()
                  .set({
                      margin: 0.5,
                      filename: 'mypdf.pdf',
                      image: {
                          type: 'jpeg',
                          quality: 0.98
                      },
                      html2canvas: {
                          scale: 2
                      },
                      jsPDF: {
                          unit: 'in',
                          format: 'a4',
                          orientation: 'portrait'
                      }
                  })
                  .from(element)
                  .save();
          }

          function exportToExcel(id) {
              console.log("Exporting to Excel...");
              var table = document.getElementById(`${id}-excel`);
              var workbook = XLSX.utils.table_to_book(table, {
                  sheet: "Sheet 1"
              });
              XLSX.writeFile(workbook, "data.xlsx");
          }
      </script>
