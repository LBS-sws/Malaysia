<?php

return array(
	'Data Entry'=>array(
		'access'=>'ZA',
		'icon'=>'fa-pencil-square-o',
		'items'=>array(
            'Company Info'=>array(
                'access'=>'ZA02',
                'url'=>'/company/index',
            ),
            'Wages Config'=>array(
                'access'=>'ZA03',
                'url'=>'/wages/index',
            ),
            'Wages Make'=>array(
                'access'=>'ZA04',
                'url'=>'/makeWages/index',
            ),
            'apply for work overtime'=>array(
                'access'=>'ZA05',
                'url'=>'/work/index',
            ),
            'Application for leave'=>array(
                'access'=>'ZA06',
                'url'=>'/leave/index',
            ),
            'Reward Apply'=>array(
                'access'=>'ZA07',
                'url'=>'/reward/index',
            ),
            'audit for work overtime'=>array(
                'access'=>'ZA08',
                //'url'=>'/employer/index',
                'url'=>'/auditWork/index?only=1',
            ),
            'audit for leave'=>array(
                'access'=>'ZA09',
                'url'=>'/auditLeave/index?only=1',
            ),
		),
	),
    //合同模塊
	'Contract'=>array(
		'access'=>'ZD',
		'icon'=>'fa-file-pdf-o',
		'items'=>array(
			'Supplemental Agreement'=>array(
				'access'=>'ZD03',
				'url'=>'/agreement/index',
			),
			'Contract Word'=>array(
				'access'=>'ZD01',
				'url'=>'/word/index',
			),
			'Contract List'=>array(
				'access'=>'ZD02',
				'url'=>'/contract/index',
			),
            'Common forms download'=>array(
                'access'=>'ZD04',//YB07
                'url'=>'/downForm/index',
            )
		),
	),
    //員工模塊
	'Employee'=>array(
		'access'=>'ZE',
		'icon'=>'fa-smile-o',
		'items'=>array(
            //員工錄入
            'Employee Info'=>array(
                'access'=>'ZE01',
                'url'=>'/employ/index',
            ),
            //在職員工列表
			'Job Employee List'=>array(
				'access'=>'ZE03',
				'url'=>'/employee/index',
			),
            //離職員工列表
            'Departure Employee List'=>array(
                'access'=>'ZE02',
                'url'=>'/departure/index',
            ),
            //員工變更列表
			'Employee Update List'=>array(
				'access'=>'ZE04',
				'url'=>'/history/index',
			),
            'audit for work overtime'=>array(
                'access'=>'ZE05',
                'url'=>'/auditWork/index?only=2',
            ),
            'audit for leave'=>array(
                'access'=>'ZE06',
                'url'=>'/auditLeave/index?only=2',
            ),
            'Staff appraisal'=>array(
                'access'=>'ZE07',
                'url'=>'/assess/index',
            ),
            'Pennants List'=>array(
                'access'=>'ZE08',
                'url'=>'/prize/index',
            ),
		),
	),
    //審核模塊
	'Audit'=>array(
		'access'=>'ZG',
		'icon'=>'fa-legal',
		'items'=>array(
            //入職審核
            'Employee Audit'=>array(
                'access'=>'ZG01',
                'url'=>'/audit/index',
            ),
            //變更審核
			'Employee Update Audit'=>array(
				'access'=>'ZG02',
				'url'=>'/auditHistory/index',
			),
            //工資單審核
			'Wages Audit'=>array(
				'access'=>'ZG03',
				'url'=>'/auditWages/index',
			),
            'audit for work overtime'=>array(
                'access'=>'ZG04',
                //'url'=>'/employer/index',
                'url'=>'/auditWork/index?only=3',
            ),
            'audit for leave'=>array(
                'access'=>'ZG05',
                'url'=>'/auditLeave/index?only=3',
            ),
            'Reward Audit'=>array(
                'access'=>'ZG06',
                'url'=>'/auditReward/index',
            ),
            'Pennants Audit'=>array(
                'access'=>'ZG07',
                'url'=>'/auditPrize/index',
            ),
		),
	),
    'review'=>array(
        'access'=>'RE',
        'icon'=>'fa-life-ring',
        'items'=>array(
            'Review Allot'=>array( //分配員工
                'access'=>'RE01',
                'url'=>'/ReviewAllot/index',
            ),
            'Review Handle'=>array( //優化人才評估
                'access'=>'RE02',
                'url'=>'/ReviewHandle/index',
            ),
            'Review Search'=>array( //查詢
                'access'=>'RE03',
                'url'=>'/ReviewSearch/index',
            ),
            'Review Set'=>array( //評估選項設置
                'access'=>'RE04',
                'url'=>'/ReviewSet/index',
            ),
            'Review Template'=>array( //分配模板
                'access'=>'RE05',
                'url'=>'/Template/index',
            ),
            'Review Template Employee'=>array( //員工綁定考核模板
                'access'=>'RE06',
                'url'=>'/TemplateEmployee/index',
            ),
            'Email Hint'=>array( //郵件提醒
                'access'=>'RE07',
                'url'=>'/Email/index',
            ),
        ),
    ),
    //中央技术支持
    'technical support'=>array(
        'access'=>'AY',
        'icon'=>'fa-grav',
        'items'=>array(
            'Apply technical support'=>array( //申请中央技术支持
                'access'=>'AY01',
                'url'=>'/SupportApply/index',
            ),
            'Audit technical support'=>array( //審核中央技术支持
                'access'=>'AY02',
                'url'=>'/SupportAudit/index',
            ),
            'Search technical support'=>array( //查詢中央技术支持
                'access'=>'AY03',
                'url'=>'/SupportSearch/index',
            ),
            'Search support employee'=>array( //員工支援概況
                'access'=>'AY04',
                'url'=>'/SupportEmployee/index',
            ),
        ),
    ),
	'System Setting'=>array(
		'access'=>'ZC',
		'icon'=>'fa-gear',
		'items'=>array(
			'Department'=>array(
				'access'=>'ZC01',
				//'url'=>'/employer/index',
				'url'=>'/dept/index',
			),
			'Leader'=>array(
				'access'=>'ZC02',
				'url'=>'/dept/index?type=1',
			),
			'Fete Config'=>array(
				'access'=>'ZC03',
				'url'=>'/fete/index',
			),
			'Vacation Type Config'=>array(
				'access'=>'ZC12',
				'url'=>'/vacationType/index',
			),
			'Vacation Config'=>array(
				'access'=>'ZC04',
				'url'=>'/vacation/index',
			),
			'cumulative annual leave'=>array(
				'access'=>'ZC07',
				'url'=>'/YearDay/index',
			),
			'employee binding account'=>array(
				'access'=>'ZC05',
				'url'=>'/binding/index',
			),
			'Reward Config'=>array(
				'access'=>'ZC06',
				'url'=>'/rewardCon/index',
			),
/*			'Audit Config'=>array(
				'access'=>'ZC08',
				'url'=>'/AuditConfig/index',
			),*/
			'City index'=>array(
				'access'=>'ZC09',
				'url'=>'/city/index',
			),
            'audit for work overtime'=>array(
                'access'=>'ZC10',
                'url'=>'/auditWork/index?only=4',
            ),
            'audit for leave'=>array(
                'access'=>'ZC11',
                'url'=>'/auditLeave/index?only=4',
            ),
		),
	),
	'Report'=>array(
		'access'=>'YB',
		'icon'=>'fa-file-text-o',
		'items'=>array(
			'Staff List'=>array(
				'access'=>'YB04',
				'url'=>'#',
				'hidden'=>true,
			),
			'Overtime records List'=>array(
				'access'=>'YB02',
                'url'=>'/report/overtimelist',
			),
			'Leave record List'=>array(
				'access'=>'YB03',
                'url'=>'/report/leavelist',
			),
			'Pennants ex List'=>array(
				'access'=>'YB05',
                'url'=>'/report/pennantexlist',
			),
			'Pennants cumulative List'=>array(
				'access'=>'YB06',
                'url'=>'/report/pennantculist',
			),
			'Optimize assessment report'=>array(
				'access'=>'YB07',
                'url'=>'/report/reviewlist',
			),
			'Report Manager'=>array(
				'access'=>'YB01',
                'url'=>'/queue/index',
			),
		),
	),
);
