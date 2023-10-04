<div class="sidebar-wrapper" data-simplebar="true">
  <div class="sidebar-header">
    <div>
            <img
              src="/assets/images/logo-PERCIK-PNG-1.png"
              class="img-fluid"
              alt="logo icon"
              width="150"
            />
          </div>
     <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
  </div>
  <!--navigation-->
  <ul class="metismenu" id="menu">
    {{-- @dd($userMenus) --}}
    @foreach ($userMenus as $menu)
        @if($menu['count_submenus'] == 0)
          <li>
            <a href="{{ route($menu['menu_route']) }}">
              <div class="parent-icon"><i class="{{$menu['menu_icon']}}"></i></div>
              <div class="menu-title">{{$menu['menu_name']}}</div>
            </a>
          </li>
        @else
        @php
            $route = $menu['menu_route'] == 'javascript:;' ? 'javascript:;' : route($menu['menu_route']);
        @endphp
       
        <li>
          <a href="{{$route}}" class="has-arrow">
            <div class="parent-icon"><i class="{{$menu['menu_icon']}}"></i></div>
            <div class="menu-title">{{$menu['menu_name']}}</div>
          </a>
          <ul>
            @foreach ($menu['subMenus'] as $submenu)
            <li>
              <a href="{{route($submenu->menu_route)}}">
              <i class="bx bx-right-arrow-alt"></i>{{$submenu->menu_name}}</a>
            </li>
            @endforeach
          </ul>
        </li>
        @endif
    @endforeach
  </ul>  
  <!--end navigation-->
</div>
