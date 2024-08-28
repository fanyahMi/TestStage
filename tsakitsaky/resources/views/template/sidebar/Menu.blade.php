<ul class="sidebar-menu scrollable pos-r">

    <li class="nav-item dropdown">
        <a class="dropdown-toggle" href="javascript:void(0);">
          <span class="icon-holder">
            <i class="c-orange-500 ti-layout-list-thumb"></i>
          </span>
          <span class="title">Pack</span>
          <span class="arrow">
            <i class="ti-angle-right"></i>
          </span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a class="sidebar-link" href="{{ url('listPack') }}">Liste</a>
          </li>
          <li>
            <a class="sidebar-link" href="{{ url('exportState') }}">Export Statistique</a>
          </li>
          <li>
            <a class="sidebar-link" href="{{ url('statistique') }}">Statistique</a>
          </li>
        </ul>

    </li>


    <li class="nav-item dropdown">
        <a class="dropdown-toggle" href="javascript:void(0);">
          <span class="icon-holder">
            <i class="fas fa-ticket-alt"></i>
          </span>
          <span class="title">Ticket</span>
          <span class="arrow">
            <i class="ti-angle-right"></i>
          </span>
        </a>
        <ul class="dropdown-menu">
          <li>
            <a class="sidebar-link" href="{{ url('windTicket') }}">Vente</a>
          </li>
        </ul>
        <ul class="dropdown-menu">
          <li>
            <a class="sidebar-link" href="{{ url('ticketPayment') }}">Paiment</a>
          </li>
        </ul>
        <ul class="dropdown-menu">
          <li>
            <a class="sidebar-link" href="{{ url('ticketsold') }}">Vendu</a>
          </li>
        </ul>
    </li>

     <li class="nav-item ">
        <a class="sidebar-link" href="{{url('listProducts')}}" >
          <span class="icon-holder">
            <i class="fas fa-box"></i>
          </span>
          <span class="title">Produits</span>
        </a>
      </li>
     <li class="nav-item ">
        <a class="sidebar-link" href="{{url('customers')}}" >
          <span class="icon-holder">
            <i class="fas fa-users"></i>
          </span>
          <span class="title">Clients</span>
        </a>
      </li>

      <li class="nav-item ">
        <a class="sidebar-link" href="{{url('axes')}}" >
          <span class="icon-holder">
            <i class="fas fa-grip-horizontal"></i>
          </span>
          <span class="title">Axes</span>
        </a>
      </li>

      <li class="nav-item ">
        <a class="sidebar-link" href="{{url('delevery')}}" >
          <span class="icon-holder">
            <i class="fas fa-truck"></i>
          </span>
          <span class="title">Livraisons</span>
        </a>
      </li>


    <li class="nav-item mT-30">
      <a class="sidebar-link" href="/" >
        <span class="icon-holder">
          <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Simple</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="sidebar-link" href="{{ url('export/csv') }}">
        <span class="icon-holder">
          <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Export CSV</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="sidebar-link" href="{{ url('export/excel') }}">
        <span class="icon-holder">
          <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Export Excels</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="sidebar-link" href="{{ url('pdf') }}">
        <span class="icon-holder">
          <i class="c-blue-500 ti-home"></i>
        </span>
        <span class="title">Pdf</span>
      </a>
    </li>

    <li class="nav-item">
        <a class="sidebar-link" href="{{ url('formgeneralize') }}">
          <span class="icon-holder">
            <i class="c-blue-500 ti-home"></i>
          </span>
          <span class="title">Formulaire</span>
        </a>
      </li>
   <li class="nav-item dropdown">
      <a class="dropdown-toggle" href="javascript:void(0);">
        <span class="icon-holder">
          <i class="c-orange-500 ti-layout-list-thumb"></i>
        </span>
        <span class="title">List</span>
        <span class="arrow">
          <i class="ti-angle-right"></i>
        </span>
      </a>
      <ul class="dropdown-menu">
        <li>
          <a class="sidebar-link" href="#">option A</a>
        </li>
        <li>
          <a class="sidebar-link" href="#">option B</a>
        </li>
      </ul>
    </li>
</ul>
