<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_menu_categories extends CI_Migration {

	var $table = 'Menu_category';

	public function up () {


		$this->dbforge->add_field( array(
			'menu_category_id'          => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'menu_category_name'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'menu_category_description' => array(
				'type' => 'TEXT',
			)
		) );
		$this->dbforge->add_key( 'menu_category_id', TRUE );
		$this->dbforge->add_key( 'menu_category_name' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
