<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/permissions*") ? "c-show" : "" }} {{ request()->is("admin/roles*") ? "c-show" : "" }} {{ request()->is("admin/users*") ? "c-show" : "" }} {{ request()->is("admin/audit-logs*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.permissions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/permissions") || request()->is("admin/permissions/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.roles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/roles") || request()->is("admin/roles/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('audit_log_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.audit-logs.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-file-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.auditLog.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('cm_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/companies*") ? "c-show" : "" }} {{ request()->is("admin/company-users*") ? "c-show" : "" }} {{ request()->is("admin/product-services*") ? "c-show" : "" }} {{ request()->is("admin/leads*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-parachute-box c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.cm.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('company_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.companies.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/companies") || request()->is("admin/companies/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-building c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.company.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('company_user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.company-users.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/company-users") || request()->is("admin/company-users/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-user-astronaut c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.companyUser.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('product_service_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.product-services.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/product-services") || request()->is("admin/product-services/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-box-open c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.productService.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('lead_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.leads.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/leads") || request()->is("admin/leads/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-id-card-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.lead.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @can('main_crm_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is("admin/lead-channels*") ? "c-show" : "" }} {{ request()->is("admin/lead-statuses*") ? "c-show" : "" }} {{ request()->is("admin/lead-conversions*") ? "c-show" : "" }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-adjust c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.mainCrm.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('lead_channel_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.lead-channels.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lead-channels") || request()->is("admin/lead-channels/*") ? "c-active" : "" }}">
                                <i class="fa-fw fab fa-adversal c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.leadChannel.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('lead_status_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.lead-statuses.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lead-statuses") || request()->is("admin/lead-statuses/*") ? "c-active" : "" }}">
                                <i class="fa-fw fas fa-battery-half c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.leadStatus.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('lead_conversion_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route("admin.lead-conversions.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/lead-conversions") || request()->is("admin/lead-conversions/*") ? "c-active" : "" }}">
                                <i class="fa-fw far fa-comment-dots c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.leadConversion.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>