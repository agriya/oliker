<?php
$menus = array(
    'messages' => array(
        'title' => 'Messages',
        'icon_template' => '<span class="fa fa-newspaper-o"></span>',
      'order' => 5
    ) ,
);
$tables = array(
    'messages' => array (
        'listview' => array (
            'fields' => array (
                0 => array(
                    'name' => 'id',
                    'label' => 'ID'
                ) ,
                1 => array(
                    'name' => 'user.username',
                    'label' => 'Sender',
                ) ,
                2 => array(
                    'name' => 'other_user.username',
                    'label' => 'Receiver',
                ) ,
                4 => array(
                    'name' => 'message_content.message',
                    'label' => 'Message',
                ) ,
            ) ,
            'title' => 'Messages',
            'perPage' => '10',
            'sortField' => '',
            'sortDir' => '',
            'infinitePagination' => false,
            'listActions' => array(
                0 => 'show',
                1 => 'delete'
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
                    'name' => 'user_id',
                    'label' => 'From User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
                    'type' => 'reference',
                    'remoteComplete' => true,
                ) ,
                2 => array(
                    'name' => 'other_user_id',
                    'label' => 'To User',
                    'targetEntity' => 'users',
                    'targetField' => 'username',
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
                        0=> 'delete'
            ),
        ) ,
        'creationview' => array(
            'fields' => array(
                0 => array(
                    'name' => 'user.name',
                    'label' => 'User',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                1 => array(
                    'name' => 'other_user.name',
                    'label' => 'Other User',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                2 => array(
                    'name' => 'ad.name',
                    'label' => 'Ad',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                3 => array(
                    'name' => 'message_content.name',
                    'label' => 'Message Content',
                    'type' => 'number',
                    'validation' => array(
                        'required' => true,
                    ) ,
                ) ,
                4 => array(
                    'name' => 'is_sender',
                    'label' => 'Is Sender',
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
                5 => array(
                    'name' => 'is_read',
                    'label' => 'Is Read',
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
                    'name' => 'is_archived',
                    'label' => 'Is Archived',
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
            'title' => 'Messages',
        ) ,
            'showview' => 
    array (
      'fields' => 
      array (
        0 => 
        array (
          'name' => 'id',
          'label' => 'id',
          'isDetailLink' => true,
        ),
        1 => 
        array (
          'name' => 'user.username',
          'label' => 'Sender',
        ),
        2 => 
        array (
          'name' => 'other_user.username',
          'label' => 'Receiver',
        ),
        3 => 
        array (
          'name' => 'ad.title',
          'label' => 'Ad',
        ),
        4 => 
        array (
          'name' => 'message_content.message',
          'label' => 'Message Content',
        ),
         4 => 
        array (
          'name' => '',
          'label' => 'Attachment',
          'template' => '<display-image entry="entry" type="Message" thumb="normal_thumb" entity="entity"></display-image>',
        ),
        
      ),
      
      'title' => 'Messages',
    ),
    ) ,
);
$dashboard = array (
  'messages' => array (
    'addCollection' => array (
      'fields' => array (
        0 => array (
          'name' => 'id',
          'label' => 'ID'
        ),
        1 => array (
          'name' => 'user.username',
          'label' => 'Sender'
        ),
        2 => array (
          'name' => 'other_user.username',
          'label' => 'Receiver'
        ),
        3 => array (
          'name' => 'ad.title',
          'label' => 'AD',
        ),
        4 => array (
          'name' => 'message_content.message',
          'label' => 'Message',
        )
      ),
      'title' => 'Recent Messages',
      'name' => 'recent_messages',
      'perPage' => 5,
      'order' => 3,
      'template' => '<div class="col-lg-6"><div class="panel"><ma-dashboard-panel collection="dashboardController.collections.recent_messages" entries="dashboardController.entries.recent_messages" datastore="dashboardController.datastore"> </ma-dashboard-panel></div></div>'
    )
  )
);

if(isPluginEnabled('Ad/Ad')) {
  $milestone_table = array (
    'messages' => array (
      'listview' => array (
        'fields' => array (
         3 => array(
                    'name' => 'ad.title',
                    'label' => 'AD',
                ) ,
        )
      ),
    )
  );
 $tables = merge_details($tables, $milestone_table);
}
?>