@php
    $c = Request::segment(1);
    $m = Request::segment(2);
    $roleName = Auth::user()->getRoleNames();
@endphp

<aside class="main-sidebar sidebar-light-info elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link sidebar-light-info">
        <img style="width:200px;" src="https://blanchebeautybar.com/wp-content/uploads/2024/08/Web-Logo-1.png" />
    </a>
    <div class="sidebar">
        <?php
        if (Auth::user()->photo == null) {
            $photo = 'assets/images/profile/male.png';
        } else {
            $photo = Auth::user()->photo;
        }
        ?>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @canany(['dashboard-read'])
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link @if ($c == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('Dashboard') }}</p>
                        </a>
                    </li>
                @endcanany


                @canany(['doctor-detail-read', 'doctor-detail-create', 'doctor-detail-update', 'doctor-detail-delete'])
                    <li class="nav-item">
                        <a href="{{ route('doctor-details.index') }}"
                            class="nav-link @if ($c == 'employee') active @endif">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>@lang('Employee')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['patient-detail-read', 'patient-detail-create', 'patient-detail-update',
                    'patient-detail-delete'])
                    <li class="nav-item">
                        <a href="{{ route('patient-details.index') }}"
                            class="nav-link @if ($c == 'customer') active @endif">
                            <i class="nav-icon fas fa-users"></i>
                            <p>@lang('Customers')</p>
                        </a>
                    </li>
                @endcanany
                @canany(['office-consume-read'])
                    <li class="nav-item">
                        <a href="{{ route('consume.index', ['type' => 'ofs_items']) }}"
                            class="nav-link @if ($c == 'consume' && request()->query('type') == 'ofs_items') active @endif">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>@lang('Office Consume (Approval)')</p>
                        </a>
                    </li>
                @endcan

                @canany(['saloon-consume-read'])
                    <li class="nav-item">
                        <a href="{{ route('consume.index', ['type' => 'sln_items']) }}"
                            class="nav-link @if ($c == 'consume' && request()->query('type') == 'sln_items') active @endif">
                            <i class="nav-icon fas fa-notes-medical"></i>
                            <p>@lang('Salon Consume (Approval)')</p>
                        </a>
                    </li>
                @endcan

                @canany(['sale-invoice-read'])
                    <li class="nav-item">
                        <a href="{{ route('invoice.index', ['type' => 'sale', 'approve' => 'saleapproval']) }}"
                            class="nav-link @if (request()->query('type') == 'sale' && request()->query('approve') == 'saleapproval' && !request()->has('report')) active @endif">
                            <i class="nav-icon fas fa-file-invoice-dollar"></i>
                            <p>@lang('Sale Invoice (Approval)')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['sale-invoice-read'])

                @endcanany





                @canany(['inventory-read', 'inventory-create', 'inventory-update', 'inventory-delete'])
                    <li class="nav-item">
                        <a href="{{ route('inventories.index', ['type' => 'ofs_items']) }}"
                            class="nav-link @if (request()->query('type') == 'ofs_items' && $c == 'inventories') active @endif">
                            <i class="nav-icon fas fa-box"></i>
                            <p>@lang('Office Inventory')</p>
                        </a>
                    </li>
                @endcanany

                @canany(['salon-inventory-read', 'salon-inventory-create', 'salon-inventory-update',
                    'salon-inventory-delete'])
                    <li class="nav-item">
                        <a href="{{ route('inventories.index', ['type' => 'sln_items']) }}"
                            class="nav-link @if (request()->query('type') == 'sln_items' && $c == 'inventories') active @endif">
                            <i class="nav-icon fas fa-box"></i>
                            <p>@lang('Salon Inventory')</p>
                        </a>
                    </li>
                @endcanany


                <li class="nav-item">
                    <a href="{{ route('invoice.index', ['type' => 'sale']) }}"
                        class="nav-link @if (request()->query('type') == 'sale' && (request()->query('approve') != 'saleapproval' || !request()->has('approve'))) active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>@lang('Sale Invoices (Lists)')</p>
                    </a>
                </li>

                {{-- @canany(['invoice-read', 'invoice-create', 'invoice-update', 'invoice-delete']) --}}
                <li class="nav-item">
                    <a href="{{ route('invoice.index', ['type' => 'services']) }}"
                        class="nav-link  @if (request()->query('type') == 'services') active @endif">
                        <i class="nav-icon fas fa-file-invoice-dollar"></i>
                        <p>@lang('Services Invoices (Lists)')</p>
                    </a>
                </li>
                {{-- @endcanany --}}




                @canany(['dropdown-read', 'dropdown-create', 'dropdown-update', 'dropdown-delete'])
                    <li class="nav-item has-treeview @if (
                        $c == 'dd-blood-groups' ||
                            $c == 'dd-procedures' ||
                            $c == 'dd-medicine' ||
                            $c == 'inventory' ||
                            $c == 'items' ||
                            $c == 'categories' ||
                            $c == 'dd-social-history' ||
                            $c == 'dd-medical-history' ||
                            $c == 'dd-procedure-categories' ||
                            $c == 'dd-dental-history' ||
                            $c == 'dd-drug-history' ||
                            $c == 'appointment-statuses' ||
                            $c == 'dd-investigations' ||
                            $c == 'dd-treatment-plans' ||
                            $c == 'dd-services' ||
                            $c == 'dd-examinations' ||
                            $c == 'dd-services-categorie' ||
                            $c == 'dd-medicine-types') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if (
                            $c == 'dd-blood-groups' ||
                                $c == 'dd-procedures' ||
                                $c == 'dd-medicine' ||
                                $c == 'dd-diagnosis' ||
                                $c == 'items' ||
                                $c == 'inventory' ||
                                $c == 'categories' ||
                                $c == 'dd-social-history' ||
                                $c == 'dd-procedure-categories' ||
                                $c == 'dd-dental-history' ||
                                $c == 'dd-examinations' ||
                                $c == 'dd-services-categorie' ||
                                $c == 'dd-treatment-plans' ||
                                $c == 'dd-services' ||
                                $c == 'marital-statuses' ||
                                $c == 'dd-medicine-types') active @endif">
                            <i class="nav-icon fas fa-list"></i>
                            <p>
                                @lang('Dropdowns')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['ofs-category-read'])
                                <li class="nav-item">
                                    <a href="{{ route('categories.index', ['type' => 'ofs_items']) }}"
                                        class="nav-link @if (request()->query('type') == 'ofs_items' && $c == 'categories') active @endif">
                                        <i class="nav-icon fas fa-tags"></i>
                                        <p>@lang('Office Category')</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('categories.index', ['type' => 'sln_items']) }}"
                                    class="nav-link @if (request()->query('type') == 'sln_items' && $c == 'categories') active @endif">
                                    <i class="nav-icon fas fa-tags"></i>
                                    <p>@lang('Salon Category')</p>
                                </a>
                            </li>
                            @canany(['item-read'])
                                <li class="nav-item">
                                    <a href="{{ route('items.index', ['type' => 'ofs_items']) }}"
                                        class="nav-link @if (request()->query('type') == 'ofs_items' && $c == 'items') active @endif">
                                        <i class="nav-icon fas fa-boxes"></i>
                                        <p>@lang('Office Items')</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('items.index', ['type' => 'sln_items']) }}"
                                    class="nav-link @if (request()->query('type') == 'sln_items' && $c == 'items') active @endif">
                                    <i class="nav-icon fas fa-boxes"></i>
                                    <p>@lang('Salon Items')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-procedure-categories.index') }}"
                                    class="nav-link @if ($c == 'dd-services-categorie') active @endif ">
                                    <i class="nav-icon fas fa-folder-plus"></i>
                                    <p>@lang('Services Category')</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-procedures.index') }}"
                                    class="nav-link @if ($c == 'dd-services') active @endif ">
                                    <i class="nav-icon fas fa-tools"></i>
                                    <p>@lang('Services')</p>
                                </a>

                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dd-enquirysource.index') }}"
                                    class="nav-link @if ($c == 'dd-enquirysource') active @endif ">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>@lang('Enquiry Source')</p>
                                </a>
                            </li>
                        </ul>


                    </li>
                @endcanany
               @canany(['account-header-read', 'account-header-create', 'account-header-update', 'account-header-delete', 'financial-report-read'])
    <li class="nav-item has-treeview @if (
        $c == 'account-headers' ||
        $c == 'invoices' ||
        $c == 'inventories_purchased' ||
        $c == 'inventory_sold' ||
        $c == 'reports' ||
        $c == 'new-reports' ||
        $c == 'payments' ||
        $c == 'financial-reports') menu-open @endif">
        <a href="javascript:void(0)" class="nav-link @if (
            $c == 'account-headers' ||
            $c == 'inventories_purchased' ||
            $c == 'inventory_sold' ||
            $c == 'reports' ||
            $c == 'invoices' ||
            $c == 'new-reports' ||
            $c == 'services' ||
            $c == 'sale' ||
            $c == 'payments' ||
            $c == 'financial-reports') active @endif">
            <i class="nav-icon fas fa-hand-holding-usd"></i>
            <p>
                @lang('Financial Activities')
                <i class="right fas fa-angle-left"></i>
            </p>
        </a>
        <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('payments.index') }}"
                    class="nav-link @if ($c == 'payments') active @endif ">
                    <i class="nav-icon fas fa-money-check"></i>
                    <p>@lang('Expense')</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('new-reports.index') }}"
                    class="nav-link @if ($c == 'new-reports') active @endif ">
                    <i class="nav-icon fas fa-file-invoice"></i>
                    <p>@lang('Invoice Report')</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('invoice.index', ['type' => 'sale', 'approve' => 'saleapproval', 'report' => 'reportinventory']) }}"
                    class="nav-link @if (request()->query('type') == 'sale' &&
                        request()->query('approve') == 'saleapproval' &&
                        request()->query('report') == 'reportinventory') active @endif">
                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                    <p>@lang('Inventory Sold')</p>
                </a>
            </li>
            @canany(['consume-read'])
                <li class="nav-item">
                    <a href="{{ route('consume.index') }}"
                        class="nav-link @if ($c == 'consume') active @endif">
                        <i class="nav-icon fas fa-notes-medical"></i>
                        <p>@lang('Inventory Consumed')</p>
                    </a>
                </li>
            @endcan
            <li class="nav-item">
                <a href="{{ route('inventories_purchased') }}"
                    class="nav-link @if ($c == 'inventories_purchased') active @endif">
                    <i class="nav-icon fas fa-dollar-sign"></i>
                    <p>@lang('Inventory Purchases')</p>
                </a>
            </li>
        </ul>
    </li>
@endcanany





                @canany(['role-read', 'role-create', 'role-update', 'role-delete', 'user-read', 'user-create',
                    'user-update', 'user-delete', 'smtp-read', 'smtp-create', 'smtp-update', 'smtp-delete', 'company-read',
                    'company-create', 'company-update', 'company-delete', 'currencies-read', 'currencies-create',
                    'currencies-update', 'currencies-delete', 'tax-rate-read', 'tax-rate-create', 'tax-rate-update',
                    'tax-rate-delete'])
                    <?php //if(Auth::user()->hasRole('Super Admin')){
                    ?>
                    <li class="nav-item has-treeview @if (
                        $c == 'roles' ||
                            $c == 'users' ||
                            $c == 'apsetting' ||
                            $c == 'smtp-configurations' ||
                            $c == 'general' ||
                            $c == 'currency' ||
                            $c == 'tax') menu-open @endif">
                        <a href="javascript:void(0)" class="nav-link @if (
                            $c == 'roles' ||
                                $c == 'users' ||
                                $c == 'apsetting' ||
                                $c == 'smtp-configurations' ||
                                $c == 'general' ||
                                $c == 'currency' ||
                                $c == 'tax') active @endif">
                            <i class="nav-icon fa fa-cogs"></i>
                            <p>
                                @lang('Settings')
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @canany(['role-read', 'role-create', 'role-update', 'role-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('roles.index') }}"
                                        class="nav-link @if ($c == 'roles') active @endif ">
                                        <i class="fas fa-cube nav-icon"></i>
                                        <p>@lang('Role Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @canany(['user-read', 'user-create', 'user-update', 'user-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link @if ($c == 'users') active @endif ">
                                        <i class="fa fa-users nav-icon"></i>
                                        <p>@lang('User Management')</p>
                                    </a>
                                </li>
                            @endcanany
                            @if ($roleName['0'] = 'Super Admin')
                                <li class="nav-item">
                                    <a href="{{ route('apsetting') }}"
                                        class="nav-link @if ($c == 'apsetting' && $m == null) active @endif ">
                                        <i class="fa fa-globe nav-icon"></i>
                                        <p>@lang('Application Settings')</p>
                                    </a>
                                </li>
                            @endif

                            @canany(['company-read', 'company-create', 'company-update', 'company-delete'])
                                <li class="nav-item">
                                    <a href="{{ route('general') }}"
                                        class="nav-link @if ($c == 'general') active @endif ">
                                        <i class="fas fa-align-left nav-icon"></i>
                                        <p>@lang('General Settings')</p>
                                    </a>
                                </li>
                            @endcanany



                        </ul>
                    </li>
                    {{-- <?php //}
                    ?> --}}
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
