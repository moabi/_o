<?php
if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_5839531545720',
		'title' => 'Vendeur (user role)',
		'fields' => array (
			array (
				'key' => 'field_5839531ff0a27',
				'label' => 'KBis',
				'name' => 'kbis',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'Non reçu',
					1 => 'En cours',
					2 => 'Validé',
					3 => 'Refusé',
				),
				'default_value' => array (
					0 => 0,
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'return_format' => 'array',
				'placeholder' => '',
			),
			array (
				'key' => 'field_583962d9dfc6f',
				'label' => 'Urssaf',
				'name' => 'urssaf',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'Non reçu',
					1 => 'En cours',
					2 => 'Validé',
					3 => 'Refusé',
				),
				'default_value' => array (
					0 => 0,
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'return_format' => 'array',
				'placeholder' => '',
			),
			array (
				'key' => 'field_583962e5dfc70',
				'label' => 'Pièce d\'identité',
				'name' => 'identite',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'choices' => array (
					0 => 'Non reçu',
					1 => 'En cours',
					2 => 'Validé',
					3 => 'Refusé',
				),
				'default_value' => array (
					0 => 0,
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'return_format' => 'array',
				'placeholder' => '',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'user_form',
					'operator' => '==',
					'value' => 'all',
				),
				array (
					'param' => 'user_role',
					'operator' => '==',
					'value' => 'vendor',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;