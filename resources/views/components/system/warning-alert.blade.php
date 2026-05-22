@props([
    'message' => 'worning, please check your input',
])

<div class="alert alert-warning alert-icon" role="alert">
    <i class="mdi mdi-alert-decagram-outline"></i>
        {{ $message }}
  </div>
