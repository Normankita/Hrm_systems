@props([
    'message' => 'your action was successful',
])

<div class="alert alert-success alert-icon" role="alert">
    <i class="mdi mdi-checkbox-marked-outline"></i>
        {{ $message }}
  </div>
