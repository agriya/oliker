<?php
$menus = array(
    'user_cash_withdrawals' => array(
        'title' => 'Withdrawals',
        'icon_template' => '<span class="glyphicon glyphicon-record"></span>',
    ) ,
);
$menus = array(
     'Settings' => array(
        'title' => 'Settings',
        'icon_template' => '<span class="glyphicon glyphicon-cog"></span>',
        'child_sub_menu' => array(
           'user_cash_withdrawals' => array(
            'title' => 'Withdrawals',
            'icon_template' => '<span class="glyphicon glyphicon-heart"></span>',
    ) ,
           
        ) ,
    ) ,
);
$tables = array(
    'user_cash_withdrawals' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Created',
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                2 => array(
                    'name' => 'withdrawal_status.name',
                    'label' => ' Status',
                ) ,
                3 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                4 => array(
                    'name' => 'remark',
                    'label' => 'Remarks',
                ) ,
            ) ,
            'title' => 'User Cash Withdrawals',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'show',
                2 => 'delete'
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
                    'name' => 'withdrawal_status_id',
                    'label' => 'Status',
                    'targetEntity' => 'withdrawal_statuses',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    
                ) ,
            ) ,
            'permanentFilters' => '',
            'actions' => array(
                0 => 'batch',
                1 => 'filter',
            ) ,
              'batchActions' => array(
                'delete'
            ),
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'money_transfer_account_id',
                    'label' => 'Money Transfer Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'remark',
                    'label' => 'Remarks',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
            ) ,
             'title' => 'User Cash Withdrawals',
        ) ,
        'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'money_transfer_account_id',
                    'label' => 'Money Transfer Account',
                    'targetEntity' => 'money_transfer_accounts',
                    'targetField' => 'account',
                    'editable' => false,
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                1 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'number',
                    'editable' => false,
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'remark',
                    'label' => 'Remark',
                    'type' => 'text',
                    'validation' => array(
                        'required' => false,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'withdrawal_status_id',
                    'label' => ' Status?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Pending',
                            'value' => 1,
                        ) ,
                        1 => array(
                            'label' => 'Approved',
                            'value' => 2,
                        ) ,
                        2 => array(
                            'label' => 'Rejected',
                            'value' => 3,
                        ) ,
                    ) ,
                ) ,
            ) ,
             'title' => 'User Cash Withdrawals',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'created_at',
                    'label' => 'Requested On',
                ) ,
                1 => array(
                    'name' => 'updated_at',
                    'label' => 'Updated At',
                ) ,
                2 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                3 => array(
                    'name' => 'money_transfer_account.account',
                    'label' => 'Money Transfer Account',
                ) ,
                4 => array(
                    'name' => 'withdrawal_status.name',
                    'label' => 'Withdrawal Status',
                ) ,
                5 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                6 => array(
                    'name' => 'remark',
                    'label' => 'Remark',
                ) ,
               7 => array(
                    'name' => 'withdrawal_status.name',
                    'label' => 'Status',
                ) ,
            ) ,
            
             'title' => 'User Cash Withdrawals',
             'listActions' => array(
                0 => 'edit',
                1 => 'show',
            ) ,
        ) ,
    ) ,
);
