<?php
namespace Shazzad\WpFormUi;

class Assets {

	public static $assets = [ 
		[ 
			'id'           => 'wf_datetimepicker',
			'path'         => 'datetimepicker.js',
			'dependencies' => [],
			'type'         => 'js'
		],
		[ 
			'id'           => 'wf_conditional_logic',
			'path'         => 'conditional_logic.js',
			'dependencies' => [],
			'type'         => 'js'
		],
		[ 
			'id'           => 'select2',
			'path'         => 'select2.min.js',
			'dependencies' => [],
			'type'         => 'js'
		],
		[ 
			'id'           => 'wf_form',
			'path'         => 'form.js',
			'dependencies' => [ 
				'jquery',
				'jquery-ui-sortable',
				'select2',
				'wf_datetimepicker',
				'wf_conditional_logic',
			],
			'type'         => 'js'
		],
		[ 
			'id'           => 'wf_datetimepicker',
			'path'         => 'datetimepicker.css',
			'dependencies' => [],
			'type'         => 'css'
		],
		[ 
			'id'           => 'select2',
			'path'         => 'select2.min.css',
			'dependencies' => [],
			'type'         => 'css'
		],
		[ 
			'id'           => 'wf_form',
			'path'         => 'form.css',
			'dependencies' => [ 'wf_datetimepicker', 'select2' ],
			'type'         => 'css'
		]
	];
}
