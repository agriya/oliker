<?php
$menus = array(
    'Misc' => array(
        'title' => 'Misc',
        'icon_template' => '<span class="glyphicon glyphicon-pencil"></span>',
        'child_sub_menu' => array(
            'ad_reports' => array(
                'title' => 'Ad Reports',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 4,
            ) ,
            'ad_report_types' => array(
                'title' => 'Ad Report Types',
                'icon_template' => '<span class="glyphicon glyphicon-log-out"></span>',
                 'suborder' => 5,
            ) ,            
        ) ,
        'order' => 8
    ) ,    
);
$tables = array(
    'ad_reports' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                2 => array(
                    'name' => 'ad_report_type.name',
                    'label' => 'Ad Report Type',
                ) ,
                3 => array(
                    'name' => 'user.username',
                    'label' => 'User',
                ) ,
                4 => array(
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
            ) ,
            'title' => 'Ad Reports',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'show',
                1 => 'delete',
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
                2 => array(
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
                    'name' => 'ad_id',
                    'label' => 'Ad',
                       'validation' => array(
                        'required' => true,
                    ) ,
                    'targetEntity' => 'ads',
                    'targetField' => 'title',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                 
                ) ,
                1 => array(
                    'name' => 'ad_report_type_id',
                    'label' => 'Ad Report Type',
                    'validation' => array(
                        'required' => true,
                    ) ,
                    'targetEntity' => 'ad_report_types',
                    'targetField' => 'name',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                    'remoteComplete' => true,
                    
                ) ,
                3 => array(
                    'name' => 'message',
                    'label' => 'Message',
                    'type' => 'text',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
            ) ,
             'title' => 'Ad Reports',
        ) ,
        'showview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'id',
                    'isDetailLink' => true,
                ) ,
                1 => array(
                    'name' => 'ad.title',
                    'label' => 'Ad',
                ) ,
                2 => array(
                    'name' => 'ad_report_type.name',
                    'label' => 'Ad Report Type',
                ) ,
                3 => array(
                    'name' => 'message',
                    'label' => 'Message',
                ) ,
                4 => array(
                     'name' => 'user_id',
                    'label' => 'User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'map' => array(
                        0 => 'truncate',
                    ) ,
                    'type' => 'reference',
                ) ,
            ) ,
             'title' => 'Ad Reports',
        ) ,
    ) ,
    'ad_report_types' => array(
        'listview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'id',
                    'label' => 'ID',
                    'isDetailLink' => true,
                    'detailLinkRoute' => 'show',
                ) ,
                1 => array(
                    'name' => 'name',
                    'label' => 'Name',
                ) ,
            ) ,
            'title' => 'Ad Report Types',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'edit',
                1 => 'delete',
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
            ) ,
              'title' => 'Ad Report Types',
        ) ,
      
    ) ,
);
if(isPluginEnabled('Ad/AdReport')) {
  $milestone_table = array (
    'ad_reports' => array (
      'listview' => array (
        'fields' => array (
         1 => array(
                    'name' => 'ad.title',
                    'label' => 'Ad',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}