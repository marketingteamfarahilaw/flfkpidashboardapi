<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_menus extends CI_Migration {

	var $table = 'menus';

	public function up () {
		$this->dbforge->add_field( array(
			'menu_id'            => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'menu_name'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'menu_description'   => array(
				'type' => 'TEXT',
			),
			'menu_image_url'     => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'menu_remarks'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'menu_menu_category' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => 8,
			),
			'menu_menu_type'     => array(
				'type'       => 'MEDIUMINT',
				'constraint' => 8,
			)
		) );
		$this->dbforge->add_key( 'menu_id', TRUE );
		$this->dbforge->add_key( 'menu_name' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
