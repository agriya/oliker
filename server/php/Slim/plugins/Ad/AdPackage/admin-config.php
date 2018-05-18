<?php
$menus = array(
    'Settings' => array(
        'title' => 'Settings',
        'icon_template' => '<span class="glyphicon glyphicon-gift"></span>',
        'child_sub_menu' => array(
            'ad_packages' => array(
                'title' => 'Ad Packages',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 5,
            ) ,           
        ) ,
        'order' => 6
    ) ,
    'Master' => array(
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-user"></span>',
        'child_sub_menu' => array(
            'user_ad_packages' => array(
                'title' => 'User Ad Packages',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 14,
            ) ,
           
        ) ,
        'order' => 7
    ) ,    
);
$tables = array(
    'ad_packages' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array(
                    'name' => 'validity_days',
                    'label' => 'Validity Days',
                ) ,
                4 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                5 => array(
                    'name' => 'additional_ads_allowed',
                    'label' => 'Additional Ads Allowed',
                ) ,
                6 => array(
                    'name' => 'is_unlimited_ads',
                    'label' => 'Unlimited Ads?',
                    'type' => 'boolean'
                ) ,
                7 => array(
                    'name' => 'credit_points',
                    'label' => 'Credit Points',
                ) ,
                8 => array(
                    'name' => 'points_valid_days',
                    'label' => 'Points Valid Days',
                ) ,
            ) ,
            'title' => 'Ad Packages',
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
                    'name' => 'category_id',
                    'label' => 'Category',
                     'validation' => array(
                        'required' => true,
                    ) ,
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                   
                    'type' => 'reference',
                    'remoteComplete' => true,
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
                   'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
               
                2 => array(
                    'name' => 'validity_days',
                    'label' => 'Validity Days',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'additional_ads_allowed',
                    'label' => 'Additional Ads Allowed',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_unlimited_ads',
                    'label' => 'Is Unlimited Ads',
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
                6 => array(
                    'name' => 'credit_points',
                    'label' => 'Credit Points',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'points_valid_days',
                    'label' => 'Points Valid Days',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                8 => array(
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
            'title' => 'Ad Packages',
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
                   'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
               
                2 => array(
                    'name' => 'validity_days',
                    'label' => 'Validity Days',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'additional_ads_allowed',
                    'label' => 'Additional Ads Allowed',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                5 => array(
                    'name' => 'is_unlimited_ads',
                    'label' => 'Is Unlimited Ads',
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
                6 => array(
                    'name' => 'credit_points',
                    'label' => 'Credit Points',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                7 => array(
                    'name' => 'points_valid_days',
                    'label' => 'Points Valid Days',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                8 => array(
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
            'title' => 'Ad Packages',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
                2 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
                3 => array(
                    'name' => 'validity_days',
                    'label' => 'Validity Days',
                ) ,
                4 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                5 => array(
                    'name' => 'additional_ads_allowed',
                    'label' => 'Additional Ads Allowed',
                ) ,
                6 => array(
                    'name' => 'is_unlimited_ads',
                    'label' => 'Unlimited Ads?',
                    'type' => 'boolean',
                ) ,
                7 => array(
                    'name' => 'credit_points',
                    'label' => 'Credit Points',
                ) ,
                8 => array(
                    'name' => 'points_valid_days',
                    'label' => 'Points Valid Days',
                ) ,
                9 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
                'title' =>  'ad_packages'
        ) ,
    ) ,
     'user_ad_packages' => array(
        'listview' => array(
            'fields' => array(
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
                    'name' => 'payment_gateway.name',
                    'label' => 'Payment Gateway',
                ) ,
                9 => array(
                    'name' => 'is_payment_completed',
                    'label' => 'Payment Completed?',
                    'type' => 'boolean'
                ) ,
            ) ,
            'title' => 'User Ad Packages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'filters' => array(
                0 => array(
                    'name' => 'q',
                    'pinned' => true,
                    'label' => 'Search',
                    'type' => 'template',
                    'template' => '<div class="input-group"><input type="text" ng-model="value" placeholder="Search" class="form-control"></input><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>',
                ) ,
                1 => array(
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
                2 => array(
                    'name' => 'ad_package_id',
                    'label' => 'Ad Package',
                    'targetEntity' => 'ad_packages',
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
       
    ) ,
);
if(isPluginEnabled('Ad/AdPackage')) {
  $milestone_table = array (
    'ad_packages' => array (
      'listview' => array (
        'fields' => array (
          1 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}