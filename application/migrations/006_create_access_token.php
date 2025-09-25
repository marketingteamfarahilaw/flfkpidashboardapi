<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Migration_create_access_token extends CI_Migration {

		var $table = 'access_token';

		public function up () {
			$this->dbforge->add_field( array(
				'id'            => array(
					'type'           => 'MEDIUMINT',
					'constraint'     => 8,
					'unsigned'       => TRUE,
					'auto_increment' => TRUE
				),
				'token'         => array(
					'type'       => 'VARCHAR',
					'constraint' => 100
				),
				'customer_id'   => array(
					'type'       => 'MEDIUMINT',
					'constraint' => 8,
					'unsigned'   => TRUE,
				),
				'date_accessed' => array(
					'type' => 'timestamp'
				),
				'ip'            => array(
					'type'       => 'VARCHAR',
					'constraint' => 50
				)
			) );
			$this->dbforge->add_key( 'id', TRUE );
			$this->dbforge->add_key( 'token' );
			$this->dbforge->add_key( 'customer_id' );
			$this->dbforge->add_key( 'date_accessed' );
			$this->dbforge->create_table( $this->table );

		}

		public function down () {
			$this->dbforge->drop_table( $this->table );
		}
	}
