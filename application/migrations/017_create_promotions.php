<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_promotions extends CI_Migration {

	var $table = 'promotions';

	public function up () {
		$this->dbforge->add_field( array(
			'promotion_id'          => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'promotion_title'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'promotion_place'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'promotion_description' => array(
				'type' => 'TEXT',
			),
			'promotion_date' => array(
				'type' => 'DATETIME',
			)
		) );
		$this->dbforge->add_key( 'promotion_id', TRUE );
		$this->dbforge->add_key( 'promotion_title' );
		$this->dbforge->add_key( 'promotion_date' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}

}
