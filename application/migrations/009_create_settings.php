<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_settings extends CI_Migration {

	var $table = 'settings';

	public function up () {
		$this->dbforge->add_field( array(
			'setting_id'            => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'setting_name'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'setting_description'   => array(
				'type' => 'TEXT',
			),
			'setting_default_value' => array(
				'type' => 'TEXT',
			)
		) );
		$this->dbforge->add_key( 'setting_id', TRUE );
		$this->dbforge->add_key( 'setting_name' );
		$this->dbforge->create_table( $this->table );

		$this->db->insert( $this->table, array(
			'setting_name'          => 'Subscribe to Newsletter',
			'setting_description'   => 'Customer agrees to receive notifications and promotions through email',
			'setting_default_value' => 'FALSE',
		) );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
