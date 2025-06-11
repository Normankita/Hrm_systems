@props(['title', 'route'])
<a href="{{ $route }}">
     <div class="card border-primary">
         <div class="card-body">
             <h5 class="card-title font-weight-bold">
                 {{ $title }}
             </h5>
             {{ $slot }}
         </div>
     </div>
 </a>
