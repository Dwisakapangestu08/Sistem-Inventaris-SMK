 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">



         @php
             $user = \App\Models\User::where('id', auth()->user()->id)->first();
         @endphp

         @if ($user->role == 1)
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Dashboard' ? '' : 'collapsed' }}" href="/admin">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             </li>
             <!-- End Dashboard Nav -->
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Penanggung Jawab' ? '' : 'collapsed' }}"
                     href="/admin/penanggung-jawab">
                     <i class="bi bi-people-fill"></i>
                     <span>Penanggung Jawab</span>
                 </a>
             </li>
             <!-- End Penanggung Jawab Nav -->
             <li class="nav-item">
                 {{-- <a class="nav-link {{ $title == 'Barang' ? '' : 'collapsed' }}" href="/admin/barang">
                     <i class="bi bi-box-fill"></i>
                     <span>Barang</span>
                 </a> --}}
                 <a class="nav-link {{ $title == 'Barang' ? '' : 'collapsed' }}" data-bs-target="#forms-nav"
                     data-bs-toggle="collapse" href="#">
                     <i class="bi bi-box-fill"></i><span>Barang</span><i class="bi bi-chevron-down ms-auto"></i>
                 </a>
                 <ul id="forms-nav"
                     class="nav-content collapse {{ $title == 'Barang' || $title == 'Kategori Barang' ? 'show' : '' }}"
                     data-bs-parent="#sidebar-nav">
                     <li>
                         <a href="/admin/barang" class=" {{ $title == 'Barang' ? 'active' : '' }}">
                             <i class="bi bi-circle"></i><span>List Barang</span>
                         </a>
                     </li>
                     {{-- <li>
                         <a href="/admin/kategori-barang" class=" {{ $title == 'Kategori Barang' ? 'active' : '' }}">
                             <i class="bi bi-circle"></i><span>Kategori Barang</span>
                         </a>
                     </li> --}}
                 </ul>
             </li>
             <!-- End Barang Nav -->
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Pengajuan Barang' ? '' : 'collapsed' }}"
                     href="/admin/pengajuan-barang">
                     <i class="bi bi-clipboard2-pulse-fill"></i>
                     <span>List Pengajuan</span>
                 </a>
             </li>
             <!-- End Pengajuan Nav -->
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Aktivasi Akun' ? '' : 'collapsed' }}" href="/admin/akun">
                     <i class="bi bi-check-circle-fill"></i>
                     <span>Aktivasi Akun</span>
                 </a>
             </li>
             <!-- End Barang Nav -->
         @endif
         @if ($user->role == 2)
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Dashboard' ? '' : 'collapsed' }}" href="/user">
                     <i class="bi bi-grid"></i>
                     <span>Dashboard</span>
                 </a>
             </li>
             <!-- End Dashboard Nav -->
             <li class="nav-item">
                 <a class="nav-link {{ $title == 'Pengajuan' ? '' : 'collapsed' }}" href="/user/pengajuan-barang">
                     <i class="bi bi-clipboard-plus"></i>
                     <span>Pengajuan Barang</span>
                 </a>
             </li>
             <!-- End Barang Nav -->
         @endif

     </ul>

 </aside><!-- End Sidebar-->
