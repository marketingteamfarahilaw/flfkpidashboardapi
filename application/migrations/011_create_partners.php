<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_partners extends CI_Migration {

	var $table = 'partners';

	public function up () {
		$this->dbforge->add_field( array(
			'partner_id'          => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'partner_name'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'partner_description' => array(
				'type' => 'TEXT',
			),
			'partner_image_url'   => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			)
		) );
		$this->dbforge->add_key( 'partner_id', TRUE );
		$this->dbforge->add_key( 'partner_name' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
