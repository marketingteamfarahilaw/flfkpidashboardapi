<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_menu_types extends CI_Migration {

	var $table = 'Menu_type';

	public function up () {
		$this->dbforge->add_field( array(
			'menu_type_id'          => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'menu_type_name'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'menu_type_description' => array(
				'type' => 'TEXT',
			),
			'menu_type_icon'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			)
		) );
		$this->dbforge->add_key( 'menu_type_id', TRUE );
		$this->dbforge->add_key( 'menu_type_name' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
