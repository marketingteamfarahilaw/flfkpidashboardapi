<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Migration_create_configs extends CI_Migration {

		var $table = 'configs';

		public function up () {
			$this->dbforge->add_field( array(
				'config_id'           => array(
					'type'           => 'MEDIUMINT',
					'constraint'     => 8,
					'unsigned'       => TRUE,
					'auto_increment' => TRUE
				),
				'config_name'  => array(
					'type'       => 'VARCHAR',
					'constraint' => 255,
					'unique'     => TRUE,
				),
				'config_value' => array(
					'type' => 'TEXT'
				)
			) );
			$this->dbforge->add_key( 'config_id', TRUE );
			$this->dbforge->create_table( $this->table );

		}

		public function down () {
			$this->dbforge->drop_table( $this->table );
		}
	}
