  @props(['text' => 'Export to Excel', 'id'])
  <button class="btn btn-success" onclick="exportToExcel('{{ $id }}')">
      <i class="mdi mdi-file-excel"></i> {{ $text }}
  </button>
