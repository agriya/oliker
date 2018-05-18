<?php
$menus = array(
    'Master' => array(
        'title' => 'Master',
        'icon_template' => '<span class="fa fa-user"></span>',
        'child_sub_menu' => array(
              'ad_extras' => array(
                'title' => 'Ad Extras',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 11
            ) , 
            'ad_extra_days' => array(
                'title' => 'Ad Extra Days',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 12,
            ) ,
            'user_ad_extras' => array(
                'title' => 'User Ad Extras',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 13,
            ) ,                                  
        ) ,
        'order' => 7
    ) ,
);
$tables = array(
    'ad_extras' => array(
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
                    'name' => 'is_active',
                    'label' => 'Active?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Ad Extras',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
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
                    'name' => 'is_active',
                    'label' => ' Active?',
                    'type' => 'choice',
                    'choices' => array(
                        0 => array(
                            'label' => 'Yes',
                            'value' => 'true',
                        ) ,
                        1 => array(
                            'label' => 'No',
                            'value' => 'false',
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
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
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
            'title' => 'Ad Extras',
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
            'title' => 'Ad Extras',
        ) ,
       
    ) ,
    'ad_extra_days' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                ) ,
                1 => array(
                    'name' => 'ad_extra.name',
                    'label' => 'Ad Extra',
                ) ,
                3 => array(
                    'name' => 'days',
                    'label' => 'Days',
                ) ,
                4 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                5 => array(
                    'name' => 'is_active',
                    'label' => 'Active?',

                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'Ad Extra Days',
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
                    'name' => 'category_id',
                    'label' => 'Category',
                    'targetEntity' => 'categories',
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
                2 => 'create',
            ) ,
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'ad_extra_id',
                    'label' => 'Ad Extra',
                    'targetEntity' => 'ad_extras',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
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
                ) ,
                2 => array(
                    'name' => 'days',
                    'label' => 'Days',
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
              'title' => 'Ad Extra Days',
        ) ,
          'editionview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'ad_extra_id',
                    'label' => 'Ad Extra',
                    'targetEntity' => 'ad_extras',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
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
                ) ,
                2 => array(
                    'name' => 'days',
                    'label' => 'Days',
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
              'title' => 'Ad Extra Days',
        ) ,
        
    ) ,
    'user_ad_extras' => array(
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
                3 => array(
                    'name' => 'ad_extra.name',
                    'label' => 'Ad Extra',
                ) ,
                
                4 => array(
                    'name' => 'amount',
                    'label' => 'Amount',
                ) ,
                5 => array(
                    'name' => 'payment_gateway.name',
                    'label' => 'Payment Gateway',
                ) ,
                6 => array(
                    'name' => 'is_payment_completed',
                    'label' => 'Payment Completed?',
                    'type' => 'boolean',
                ) ,
            ) ,
            'title' => 'User Ad Extras',
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
                    'name' => 'ad_id',
                    'label' => 'Ad',
                    'targetEntity' => 'ads',
                    'targetField' => 'title',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
               
            ) ,
            'permanentFilters' => '',
            'batchActions' => array(
                'delete'
            ),
            
        ) ,
    ) ,
);
if(isPluginEnabled('Ad/Ad')) {
  $milestone_table = array (
    'ad_extra_days' => array (
      'listview' => array (
        'fields' => array (
          2 => array(
                    'name' => 'category.name',
                    'label' => 'Category',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}

if(isPluginEnabled('Ad/Ad')) {
  $milestone_table = array (
    'user_ad_extras' => array (
      'listview' => array (
        'fields' => array (
          2 => array(
                    'name' => 'ad.title',
                    'label' => 'Ad',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}

