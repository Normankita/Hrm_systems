@props(['title' => 'System', 'subTitle' => 'Overview',
    'backgroundColor' => 'linear-gradient(45deg, #4e73df, #224abe); box-shadow: 0 4px 15px rgba(0,0,0,0.1)'])

<div>
      <!-- Header Section -->
      <div class="d-flex justify-content-between align-items-center mb-4 p-4 rounded"
      style="background: {{ $backgroundColor }}">
      <div class="d-flex align-items-center">
          <div class="bg-white rounded-circle p-3 me-3"
              style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
              <i class="fas fa-tachometer-alt fa-lg text-primary"></i>
          </div>
          <div>
              <h2 class="text-white mb-0"><span class="fw-light">{{ $title }}</span> Dashboard</h2>
              <small class="text-white-50">{{ $subTitle }}</small>
          </div>
      </div>
      <div class="date d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-4 py-2">
          <i class="far fa-calendar-alt fa-lg text-white me-2"></i>
          <span id="current-date" class="text-dar fw-light"></span>
      </div>
  </div>    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
