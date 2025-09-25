<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_events extends CI_Migration {

	var $table = 'events';

	public function up () {
		$this->dbforge->add_field( array(
			'event_id'             => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'event_title'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'event_description'    => array(
				'type' => 'TEXT',
			),
			'event_place'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'event_date'           => array(
				'type' => 'DATETIME'
			),
			'event_date_published' => array(
				'type' => 'DATETIME'
			),
			'event_status'         => array(
				'type' => 'SET("published", "draft")',
				'default' => 'draft'
			)
		) );
		$this->dbforge->add_key( 'event_id', TRUE );
		$this->dbforge->add_key( 'event_name' );
		$this->dbforge->add_key( 'event_status' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
