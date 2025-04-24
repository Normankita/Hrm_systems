@props([
    'message' => 'fail to make request',
])

<div class="alert alert-danger alert-icon" role="alert">
    <i class="mdi mdi-diameter-variant"></i>
    {{ $message }}
  </div>
