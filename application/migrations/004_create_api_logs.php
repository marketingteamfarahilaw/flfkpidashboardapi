<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Migration_create_api_logs extends CI_Migration {
		public function up () {
			$this->config->load( 'rest' );
			$table = config_item( 'rest_logs_table' );
			$fields = array(
				'id'            => array(
					'type'           => 'INT(11)',
					'auto_increment' => TRUE,
					'unsigned'       => TRUE,
				),
				'api_key'       => array(
					'type' => 'VARCHAR(' . config_item( 'rest_key_length' ) . ')',
				),
				'uri'           => array(
					'type' => 'VARCHAR(255)',
				),
				'method'        => array(
					'type' => 'ENUM("get","post","options","put","patch","delete")',
				),
				'params'        => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'ip_address'    => array(
					'type' => 'VARCHAR(45)',
				),
				'time'          => array(
					'type' => 'INT(11)',
				),
				'rtime'         => array(
					'type' => 'FLOAT',
					'null' => TRUE,
				),
				'authorized'    => array(
					'type' => 'VARCHAR(1)',
				),
				'response_code' => array(
					'type'    => 'SMALLINT(3)',
					'null'    => TRUE,
					'default' => 0,
				),
			);
			$this->dbforge->add_field( $fields );
			$this->dbforge->add_key( 'id', TRUE );
			$this->dbforge->create_table( $table );
			$this->db->query(add_foreign_key($table, 'api_key',
				config_item('rest_keys_table') . '(' . config_item('rest_key_column') . ')', 'CASCADE', 'CASCADE'));
		}

		public function down () {
			$table = config_item( 'rest_logs_table' );
			if ( $this->db->table_exists( $table ) ) {
				$this->db->query(drop_foreign_key($table, 'api_key'));
				$this->dbforge->drop_table( $table );
			}
		}
	}
