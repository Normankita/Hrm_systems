  @props(['text' => 'Download PDF', 'id'])
  <button class="btn btn-primary" onclick="downloadPDF('{{$id}}')">
           <i class="mdi mdi-file-pdf"></i> {{ $text }}
       </button>
