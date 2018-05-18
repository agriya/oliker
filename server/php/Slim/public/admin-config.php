<?php
$menus = array(
    'Dashboard' => array(
        'title' => 'Dashboard',
        'icon_template' => '<span class="glyphicon glyphicon-home"></span>',
        'link' => '/dashboard',
        'order' => '1'
    ) ,
    'users' => array(
        'title' => 'Users',
        'icon_template' => '<span class="glyphicon glyphicon-user"></span>',
         'order' => 4
    ) ,
    'transactions' => array(
        'title' => 'Transactions',
        'icon_template' => '<span class="glyphicon glyphicon-usd"></span>',
        'order' => 3
    ) ,
    
    'Settings' => array(
        'title' => 'Settings',
        'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
        'child_sub_menu' => array(
            'setting_categories' => array(
                'title' => 'Site Settings',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 1,
            ) ,
             'plugins' => array(
                'title' => 'Plugins',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                'link' => '/plugins',
                 'suborder' => 2,
            ) , 
            'payment_gateways' => array(
                'title' => 'Payment Gateways',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 3,
            ) ,
             'money_transfer_accounts' => array(
                'title' => 'Bank',
                'icon_template' => '<span class="glyphicon glyphicon-record"></span>',
                 'suborder' => 4,
    ) ,                                   
        ) ,
        'order' => 6
    ) ,
    'Master' => array(
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-user"></span>',
        'child_sub_menu' => array(
            'cities' => array(
                'title' => 'Cities',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 3,
            ) ,
            'states' => array(
                'title' => 'States',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 4,
            ) ,
            'countries' => array(
                'title' => 'Countries',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 5,
            ) ,
            'pages' => array(
                'title' => 'Pages',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 6,
            ) ,
            'languages' => array(
                'title' => 'Languages',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 7,
            ) ,
            'contacts' => array(
                'title' => 'Contacts',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 8,
            ) ,
            'providers' => array(
                'title' => 'Providers',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 9,
            ) ,
            'email_templates' => array(
                'title' => 'Email Templates',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 10,
            ) ,
        ) ,
        'order' => 7
    ) ,
);
$tables = array(
    'users' => array(
        'listview' => array(
            'fields' => array(
                 0 => array(
                    'name' => 'id',
                    'label' => 'Id',
                ) ,
                1 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array(
                    'name' => 'role.name',
                    'label' => 'Role',
                ) ,
                3 => array(
                    'name' => 'username',
                    'label' => 'Username',
                ) ,
                4 => array(
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                5 => array(
                    'name' => 'first_name',
                    'label' => 'Name',
                ) ,
                7 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Users',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
                3 => '<change-password entry="entry" entity="entity" size="sm" label="Change Password" ></change-password>'
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
               
                1 => array(
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'type' => 'reference',
                    'defaultValue' => '0',
                     'validation' => 
                            array (
                        'required' => true,
                     ),
                ) ,
                2 => array(
                    'name' => 'filter',
                    'label' => 'Active?',
                    'type' => 'choice',
                   'choices' => 
                     array (
                          0 => 
                             array (
                                 'label' => 'Yes',
                                 'value' => 'active',
                        ),
                         1 => 
                             array (
                                 'label' => 'No',
                                'value' => 'inactive',
                         ),
                 ),
                ) ,
            ) ,
            'permanentFilters' => '',
            'batchActions' => array(
                '<batch-deactive type="deactive" action="users" selection="selection"></batch-deactive>',
        '<batch-active type="active" action="users" selection="selection"></batch-active>',
        'delete'
            ),
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'username',
                    'label' => 'Username',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Users',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'role_id',
                    'label' => 'Role',
                    'targetEntity' => 'roles',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'gender.name',
                    'label' => 'Gender',
                     'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Male',
                            'value' => 0,
                        ) ,
                        1 => array(
                            'label' => 'Female',
                            'value' => 1,
                        ) ,
                    ) ,
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'dob',
                    'label' => 'Dob',
                    'type' => 'date',
                    'format' => 'yyyy-MM-dd',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'about_me',
                    'label' => 'About Me',
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'location',
                    'label' => 'Location',
                    'template' => '<google-places entry="entry" entity="entity"></google-places>',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'address',
                    'label' => 'Address',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'address1',
                    'label' => 'Address1',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'city.name',
                    'label' => 'City',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                10 => array(
                    'name' => 'state.name',
                    'label' => 'State',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                11 => array(
                    'name' => 'country.iso_alpha2',
                    'label' => 'Country',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                12 => array(
                    'name' => 'zip_code',
                    'label' => 'Zip Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                13 => array(
                    'name' => 'latitude',
                    'label' => 'Latitude',
                    'type' => 'string',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                14 => array(
                    'name' => 'longitude',
                    'label' => 'Longitude',
                    'type' => 'string',
                    'defaultValue' => '0',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                15 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                16 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                17 => array(
                    'name' => 'is_email_confirmed',
                    'label' => 'Email Confirmed?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                18 => array(
                    'name' => 'is_agree_terms_conditions',
                    'label' => 'Agree Terms Conditions?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Users',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'role.name',
                    'label' => 'role',
                ) ,
                2 => array(
                    'name' => 'username',
                    'label' => 'Username',
                ) ,
                3 => array(
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                4 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) ,
                5 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                6 => array(
                    'name' => 'dob',
                    'label' => 'Dob',
                ) ,
                7 => array(
                    'name' => 'about_me',
                    'label' => 'About Me',
                ) ,
                8 => array(
                    'name' => 'address',
                    'label' => 'Address',
                ) ,
                9 => array(
                    'name' => 'address1',
                    'label' => 'Address1',
                ) ,
                10 => array(
                    'name' => 'city.name',
                    'label' => 'City',
                ) ,
                11 => array(
                    'name' => 'state.name',
                    'label' => 'State',
                ) ,
                12 => array(
                    'name' => 'country.iso_alpha2',
                    'label' => 'Country',
                ) ,
                13 => array(
                    'name' => 'zip_code',
                    'label' => 'Zip Code',
                ) ,
                14 => array(
                    'name' => 'latitude',
                    'label' => 'Latitude',
                ) ,
                15 => array(
                    'name' => 'longitude',
                    'label' => 'Longitude',
                ) ,
                16 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                ) ,
                17 => array(
                    'name' => 'mobile',
                    'label' => 'Mobile',
                ) ,
                18 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                ) ,
            ) ,
        ) ,
        'title' => 'Users',
    ) ,
    'money_transfer_accounts' => array(
        'listview' => array(
            'fields' => array(
               0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
               
                1 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                3 => array(
                    'name' => 'account',
                    'label' => 'Account',
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',

                    'type' => 'boolean',
                ) ,               
            ) ,
            'title' => 'Money Transfer Accounts',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'filter',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'is_primary',
                    'label' => ' Primary?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
              'batchActions' => array(
                'delete'
            ),
        ) ,
         'showview' => 
    array (
      'fields' => 
      array (
       
        0 => 
        array (
          'name' => 'created_at',
          'label' => 'Created At',
        ), 
        1 => 
        array (
          'name' => 'account',
          'label' => 'Account',
        ),
         2 => 
        array (
              'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
        ),
        3 => 
        array (
          'name' => 'is_active',
          'label' => 'Active?',
          'type' => 'boolean',
        ),
      ),
      'title' => 'Money Transfer Account',
    ),
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'account',
                    'label' => 'Account',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,               
            ) ,
            'title' => 'Money Transfer Accounts',
        ) ,
         'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'account',
                    'label' => 'Account',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,                
            ) ,
            'title' => 'Money Transfer Account',
        ) ,
    ) ,
    'contacts' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'ip.name',
                    'label' => 'Ip',
                ) ,
                2 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) ,
                3 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                4 => array(
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                5 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                ) ,
                6 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array(
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
            ) ,
            'title' => 'Contacts',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'show',
                1 => 'delete',
            ) ,
              'batchActions' => array(
                'delete'
            ),
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'ip.ip',
                    'label' => 'Ip',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
            'title' => 'Contacts',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
            'title' => 'Contacts',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created',
                    'label' => 'Created',
                ) ,
                2 => array(
                    'name' => 'first_name',
                    'label' => 'First Name',
                ) ,
                3 => array(
                    'name' => 'last_name',
                    'label' => 'Last Name',
                ) ,
                4 => array(
                    'name' => 'email',
                    'label' => 'Email',
                ) ,
                5 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                ) ,
                6 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array(
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
            ) ,
            'title' => 'Contacts',
        ) ,
    ) ,
    'cities' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'country_name',
                    'label' => 'Country',
                ) ,
                2 => array(
                    'name' => 'state_name',
                    'label' => 'State',
                ) ,
                3 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                4 => array(
                    'name' => 'city_code',
                    'label' => 'City Code',
                ) ,
                5 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Cities',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
              'batchActions' => array(
                'delete'
            ),
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'filter',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'city_code',
                    'label' => 'City Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Cities',
        ) ,  
         'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'state_id',
                    'label' => 'State',
                    'targetEntity' => 'states',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'city_code',
                    'label' => 'City Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Cities',
        ) ,
    ) ,
    'countries' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                1 => array(
                    'name' => 'iso_alpha2',
                    'label' => 'Iso Alpha2',
                ) ,
                2 => array(
                    'name' => 'iso_alpha3',
                    'label' => 'Iso Alpha3',
                ) ,
            ) ,
            'title' => 'Countries',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
              'batchActions' => array(
                'delete'
            ),
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'fips_code',
                    'label' => 'Fips Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso_alpha2',
                    'label' => 'Iso Alpha2',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'iso_alpha3',
                    'label' => 'Iso Alpha3',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'iso_numeric',
                    'label' => 'Iso Numeric',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'capital',
                    'label' => 'Capital',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'population',
                    'label' => 'Population',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'continent',
                    'label' => 'Continent',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'tld',
                    'label' => 'Tld',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'currency',
                    'label' => 'Currency',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                10 => array(
                    'name' => 'currencyname',
                    'label' => 'Currencyname',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                11 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                12 => array(
                    'name' => 'postalcodeformat',
                    'label' => 'Postalcodeformat',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                13 => array(
                    'name' => 'postalcoderegex',
                    'label' => 'Postalcoderegex',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                14 => array(
                    'name' => 'languages',
                    'label' => 'Languages',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                15 => array(
                    'name' => 'geonameid',
                    'label' => 'Geonameid',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                16 => array(
                    'name' => 'neighbours',
                    'label' => 'Neighbours',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                17 => array(
                    'name' => 'equivalentfipscode',
                    'label' => 'Equivalentfipscode',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
            'title' => 'Countries'
        ) ,
          'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'fips_code',
                    'label' => 'Fips Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso_alpha2',
                    'label' => 'Iso Alpha2',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'iso_alpha3',
                    'label' => 'Iso Alpha3',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'iso_numeric',
                    'label' => 'Iso Numeric',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'capital',
                    'label' => 'Capital',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'population',
                    'label' => 'Population',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'continent',
                    'label' => 'Continent',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'tld',
                    'label' => 'Tld',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                9 => array(
                    'name' => 'currency',
                    'label' => 'Currency',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                10 => array(
                    'name' => 'currencyname',
                    'label' => 'Currencyname',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                11 => array(
                    'name' => 'phone',
                    'label' => 'Phone',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                12 => array(
                    'name' => 'postalcodeformat',
                    'label' => 'Postalcodeformat',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                13 => array(
                    'name' => 'postalcoderegex',
                    'label' => 'Postalcoderegex',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                14 => array(
                    'name' => 'languages',
                    'label' => 'Languages',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                15 => array(
                    'name' => 'geonameid',
                    'label' => 'Geonameid',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                16 => array(
                    'name' => 'neighbours',
                    'label' => 'Neighbours',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                17 => array(
                    'name' => 'equivalentfipscode',
                    'label' => 'Equivalentfipscode',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
            'title' => 'Countries'
        ) 
    ) ,
    'languages' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                ) ,
                3 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Languages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
              'batchActions' => array(
                'delete'
            ),
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'filter',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Languages'
        ) ,
         'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'iso2',
                    'label' => 'Iso2',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'iso3',
                    'label' => 'Iso3',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Languages',
        ) ,
    ) ,
    'transactions' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array(
                    'name' => 'to_user.username',
                    'label' => 'Receiver',
                ) ,
                3 => array( 
                    'name' => '',
                    'template' => '<display-type entry="entry" entity="entity"></display-type>',
                    'label' => 'Description',
                ) ,    
                4 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
            ) ,
            'title' => 'Transactions',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array() ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
             'batchActions' => array(
                'delete'
            ),
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
       
    ) ,
   
    'pages' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'title',
                    'label' => 'Title',
                ) ,
                2 => array(
                    'name' => 'content',
                    'label' => 'Content',
                ) ,
                3 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Pages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'batchActions' => array(
                'delete'
            ),
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Pages',
        ) ,
          'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'title',
                    'label' => 'Title',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'content',
                    'label' => 'Content',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Pages',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'title',
                    'label' => 'Title',
                ) ,
                2 => array(
                    'name' => 'content',
                    'label' => 'Content',
                ) ,
                3 => array(
                    'name' => 'meta_keywords',
                    'label' => 'Meta Keywords',
                ) ,
                4 => array(
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                ) ,
                5 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Pages',
        ) ,
    ) ,
    'settings' => array(
        'listview' => array(
             'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
            'title' => 'Settings',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'setting_category_id',
                    'label' => 'Setting Category',
                    'targetEntity' => 'setting_categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
            ) ,
            'permanentFilters' => ''
        ),
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'setting_category.name',
                    'label' => 'Setting Category',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'type',
                    'label' => 'Type',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'label',
                    'label' => 'Label',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'position',
                    'label' => 'Position',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'options',
                    'label' => 'Options',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
            'title' => 'Settings',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'editable' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                 1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'editable' => false,
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'value',
                    'label' => 'Value',
                    'type' => 'text'
                ) ,
            ) ,
            'title' => 'Settings',
            'actions' => '<ma-back-button></ma-back-button>',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'setting_category.name',
                    'label' => 'Setting Category',
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array(
                    'name' => 'value',
                    'label' => 'Value',
                ) ,
                4 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                5 => array(
                    'name' => 'type',
                    'label' => 'Type',
                ) ,
                6 => array(
                    'name' => 'label',
                    'label' => 'Label',
                ) ,
                7 => array(
                    'name' => 'position',
                    'label' => 'Position',
                ) ,
                8 => array(
                    'name' => 'options',
                    'label' => 'Options',
                ) ,
            ) ,
            'title' => 'Settings',
        ) ,
    ) ,
    'setting_categories' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
            ) ,
            'title' => 'Setting Categories',
            'perPage' => '10',
            'sortField' => 'id',
            'sortDir' => 'ASC',
            'batchActions' => array(),
            'infinitePagination' => false,
            'listActions' => '<ma-show-button entry="entry" entity="entity" size="sm" label="Config" ></ma-show-button>',
            'actions' => '<ma-filter-button filters="filters()" enabled-filters="enabledFilters" enable-filter="enableFilter()"></ma-filter-button>',
            'permanentFilters' => ''
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'Related Settings',
                    'label' => 'Related Settings',
                    'type' => 'referenced_list',
                    'targetEntity' => 'settings',
                    'targetReferenceField' => 'setting_category_id',
                    'targetFields' => array(
                        0 => array(
                            'name' => 'label',
                            'label' => 'Name',
                        ) ,
                        1 => array(
                            'name' => 'value',
                            'label' => 'Value',
                        ) ,
                    ) ,
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'listActions' => array(
                         0 => 'edit',
                        ) ,
                    'actions' => '<ma-list-button entry="entry" entity="entity" size="sm"></ma-list-button>'
                ) ,
                3 => array(
                    'name' => '',
                    'label' => '',
                    'type' => 'template',
                    'template' => ''
                ) ,
                4 => array(
                    'name' => '',
                    'label' => '',
                    'type' => 'template',
                    'template' => ''
                ) ,
            ) ,
            'title' => 'Setting Categories',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name'
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'Description'
                ) ,
                 2 => array(
                    'name' => 'Related Settings',
                    'label' => 'Related Settings',
                    'targetEntity' => 'settings',
                    'targetReferenceField' => 'setting_category_id',
                    'listActions' => array(
                              0 => 'edit',
                        ) ,
                    'targetFields' => array(
                        0 => array(
                            'name' => 'label',
                            'label' => 'Name',
                        ) ,
                        1 => array(
                            'name' => 'value',
                            'label' => 'Value',
                             'map' => array(
                        0 => 'truncate',
                           ) ,
                        ) ,
                    ) ,
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'referenced_list',
                ) ,
                3 => array(
                    'name' => '',
                    'label' => '',
                    'type' => 'template',
                    'template' => '<add-sync entry="entry" entity="entity" size="sm" label="Synchronize with ZazPay" ></add-sync>',
                ) ,
                4 => array(
                    'name' => '',
                    'label' => '',
                    'type' => 'template',
                    'template' => '<mooc-sync entry="entry" entity="entity" size="sm" label="Synchronize with Mooc Affliate" ></mooc-sync>'
                ) ,
            ) ,
             'title' => 'Setting_categories',
            'actions' => '<ma-list-button entry="entry" entity="entity" size="sm"></ma-list-button>',
        ) 
    ) ,
    'email_templates' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                ) ,
                1 => array(
                    'name' => 'from',
                    'label' => 'From',
                ) ,
                2 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
            ) ,
            'title' => 'Email Templates',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
            ) ,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
            ) ,
            'permanentFilters' => '',
             'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ),
              'batchActions' => array(
                'delete'
            ),
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'from',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'email_variables',
                    'label' => 'Email Variables',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'html_email_content',
                    'label' => 'Html Email Content',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                8 => array(
                    'name' => 'text_email_content',
                    'label' => 'Text Email Content',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
             'title' => 'Email Templates',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'display_name',
                    'label' => 'Name',
                    'editable' => false,
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'from',
                    'label' => 'From',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'html_email_content',
                    'label' => ' Content',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'email_variables',
                    'label' => 'Constant for Subject and Content',
                     'editable' => false,
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                ) ,
                3 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                4 => array(
                    'name' => 'from',
                    'label' => 'From',
                ) ,
                5 => array(
                    'name' => 'reply_to',
                    'label' => 'Reply To',
                ) ,
                6 => array(
                    'name' => 'subject',
                    'label' => 'Subject',
                ) ,
                7 => array(
                    'name' => 'email_variables',
                    'label' => 'Email Variables',
                ) ,
                8 => array(
                    'name' => 'html_email_content',
                    'label' => 'Html Email Content',
                ) ,
                9 => array(
                    'name' => 'text_email_content',
                    'label' => 'Text Email Content',
                ) ,
            ) ,
            'title' => 'Email Templates',
        ) ,
    ) ,
    'states' => array(
        'listview' => array(
            'fields' => array(
                 0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                2 => array(
                    'name' => 'country.name',
                    'label' => 'Country',
                ) ,
                3 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                4 => array(
                    'name' => 'state_code',
                    'label' => 'State Code',
                ) ,
                5 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'States',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete',
            ) ,
              'batchActions' => array(
                'delete'
            ),
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
                    'name' => 'filter',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
                2 => 'create',
            ) ,
        ) ,
    'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'state_code',
                    'label' => 'State Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
               'title' => 'States',
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'country_id',
                    'label' => 'Country',
                    'targetEntity' => 'countries',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'state_code',
                    'label' => 'State Code',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
             'title' => 'States',  
        ) ,
         
    ) ,

    'providers' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                1 => array(
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                ) ,
                2 => array(
                    'name' => 'api_key',
                    'label' => 'Client ID',
                ) ,
                3 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Providers',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
            ) ,
            'filters' => array(
                1 => array(
                    'name' => 'filter',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'active',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'inactive',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'api_key',
                    'label' => 'Api Key',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'button_class',
                    'label' => 'Button Class',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'defaultValue' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'position',
                    'label' => 'Position',
                    'type' => 'number',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
             'title' => 'Providers',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'api_key',
                    'label' => 'Client ID',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
             'title' => 'Providers',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                ) ,
                3 => array(
                    'name' => 'secret_key',
                    'label' => 'Secret Key',
                ) ,
                4 => array(
                    'name' => 'api_key',
                    'label' => 'Api Key',
                ) ,
                5 => array(
                    'name' => 'icon_class',
                    'label' => 'Icon Class',
                ) ,
                6 => array(
                    'name' => 'button_class',
                    'label' => 'Button Class',
                ) ,
                7 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                ) ,
                8 => array(
                    'name' => 'position',
                    'label' => 'Position',
                ) ,
            ) ,
             'title' => 'Providers',
        ) ,
    ) ,
    'payment_gateways' => array(
         'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'is_test_mode',
                    'label' => 'Test Mode?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Payment Gateways',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                    'type' => 'string',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                    'type' => 'string',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'wysiwyg',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'gateway_fees',
                    'label' => 'Gateway Fees',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_test_mode',
                    'label' => ' Test Mode?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                6 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'choice',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'is_enable_for_wallet',
                    'label' => 'Is Enable For Wallet',
                    'type' => 'choice',
                    'defaultValue' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => true,
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => false,
                        ) ,
                    ) ,
                ) ,
            ) ,
            'title' => 'Payment Gateways'
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'name',
                    'label' => 'Name',
                    'editable' => false,
                ) ,
                1 => array(
                    'name' => 'description',
                    'label' => 'description',
					'editable' => false,
                    
                ) ,
                2 => array(
                    'name' => 'is_test_mode',
                    'label' => '',
                    'template'=> '<payment-gateway entry="entry" entity="entity" label="Edit"></payment-gateway>',
                ) ,
               
            ) ,
            'title' => 'Payment Gateways',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                2 => array(
                    'name' => 'display_name',
                    'label' => 'Display Name',
                ) ,
                3 => array(
                    'name' => 'slug',
                    'label' => 'Slug',
                ) ,
                4 => array(
                    'name' => 'description',
                    'label' => 'Description',
                ) ,
                5 => array(
                    'name' => 'gateway_fees',
                    'label' => 'Gateway Fees',
                ) ,
                6 => array(
                    'name' => 'transaction_count',
                    'label' => 'Transaction Count',
                ) ,
                7 => array(
                    'name' => 'payment_gateway_setting_count',
                    'label' => 'Payment Gateway Setting Count',
                ) ,
                8 => array(
                    'name' => 'is_test_mode',
                    'label' => 'Is Test Mode',
                ) ,
                9 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                ) ,
                10 => array(
                    'name' => 'is_enable_for_wallet',
                    'label' => 'Is Enable For Wallet',
                ) ,
            ) ,
           'title' => 'Payment Gateways',
        ) ,
    ) ,
);

$dashboard = array (
  'users' => array (
    'addCollection' => array (
      'fields' => array (
        0 => array (
          'name' => 'role.name',
          'label' => 'Role'
        ),
        1 => array (
          'name' => 'username',
          'label' => 'Username'
        ),
        2 => array (
          'name' => 'email',
          'label' => 'Email'
        ),
        3 => array (
          'name' => 'is_email_confirmed',
          'label' => 'Email Confirmed?',
          'type' => 'boolean'
        )
      ),
      'title' => 'Recent Users',
      'name' => 'recent_users',
      'perPage' => 5,
      'order' => 1,
      'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_users" entries="dashboardController.entries.recent_users" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
    )
  )
);

if(isPluginEnabled('Ad/AdExtra')) {
  $invoice_table = array (
    'users' => array (
      'showview' => array (
        'fields' => array (
           19 => array(
                    'name' => 'user_ad_extras',
                    'label' => 'User Ad Extras',
                    'targetEntity' => 'user_ad_extras',
                    'type' => 'referenced_list',
                    'targetReferenceField' => 'user_id',
                    'targetFields' => array(
                        0 => array(
                            'name' => 'id',
                            'label' => 'id'
                        ) ,
                        1 => array(
                            'name' => 'ad.title',
                            'label' => 'Ad',
                        ) ,
                        2 => array(
                            'name' => 'ad_extra.name',
                            'label' => 'Ad Extra',
                        ) ,
                        3 => array(
                            'name' => 'ad_extra_day.days',
                            'label' => 'Ad Extra Day',
                        ) ,
                        4 => array(
                            'name' => 'amount',
                            'label' => 'Amount',
                        ) ,
                        5 => array(
                            'name' => 'payment_gateway.display_name',
                            'label' => 'Payment Gateway',
                        ) ,
                        6 => array(
                            'name' => 'is_payment_completed',
                            'label' => 'Payment Completed?',
                        ) ,
                    ),
                    'sortField' => 'id',
                    'sortDir' => 'DESC'
                ),
        )
      )
    )
  );
  $tables = merge_details($tables, $invoice_table);
}
if(isPluginEnabled('Ad/AdPackage')) {
  $invoice_table = array (
    'users' => array (
      'showview' => array (
        'fields' => array (
            20 => array(
                    'name' => 'user_ad_packages',
                    'label' => 'User Ad Packages',
                    'targetEntity' => 'user_ad_packages',
                    'targetReferenceField' => 'user_id',
                    'targetFields' => array(
                        0 => array(
                            'name' => 'id',
                            'label' => 'id',
                            'isDetailLink' => true,
                        ) ,
                        1 => array(
                            'name' => 'user.username',
                            'label' => 'User',
                        ) ,
                        2 => array(
                            'name' => 'ad_package.name',
                            'label' => 'Ad Package',
                        ) ,
                        3 => array(
                            'name' => 'allowed_ad_count',
                            'label' => 'Allowed Ad Count',
                        ) ,
                        4 => array(
                            'name' => 'points',
                            'label' => 'Points',
                        ) ,
                        5 => array(
                            'name' => 'used_points',
                            'label' => 'Used Points',
                        ) ,
                        6 => array(
                            'name' => 'expiry_date',
                            'label' => 'Expiry Date',
                        ) ,
                        7 => array(
                            'name' => 'amount',
                            'label' => 'Amount',
                        ) ,
                        8 => array(
                            'name' => 'payment_gateway.display_name',
                            'label' => 'Payment Gateway',
                        ) ,
                        9 => array(
                            'name' => 'is_payment_completed',
                            'label' => ' Payment Completed?',
                        ) ,
                    ) ,
                    // 'map' => array(
                    //     0 => 'truncate',
                    // ) ,
                    'type' => 'referenced_list',
                    'remoteComplete' => true,
                ) ,
        )
      )
    )
  );
  $tables = merge_details($tables, $invoice_table);
}
?>