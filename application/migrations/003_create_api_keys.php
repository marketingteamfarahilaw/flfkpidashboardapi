<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Migration_create_api_keys extends CI_Migration {
		public function up () {
			$this->config->load( 'rest' );
			$table = config_item( 'rest_keys_table' );
			$fields = array(
				'id'                             => array(
					'type'           => 'INT(11)',
					'auto_increment' => TRUE,
					'unsigned'       => TRUE,
				),
				'client_id'                      => array(
					'type'     => 'INT(11)',
					'unsigned' => TRUE,
				),
				config_item( 'rest_key_column' ) => array(
					'type'   => 'VARCHAR(' . config_item( 'rest_key_length' ) . ')',
					'unique' => TRUE,
				),
				'level'                          => array(
					'type' => 'INT(2)',
				),
				'ignore_limits'                  => array(
					'type'    => 'TINYINT(1)',
					'default' => 0,
				),
				'is_private_key'                 => array(
					'type'    => 'TINYINT(1)',
					'default' => 0,
				),
				'ip_addresses'                   => array(
					'type' => 'TEXT',
					'null' => TRUE,
				),
				'date_created'                   => array(
					'type' => 'INT(11)',
				),
			);
			$this->dbforge->add_field( $fields );
			$this->dbforge->add_key( 'id', TRUE );
			$this->dbforge->create_table( $table );
			$this->db->query( add_foreign_key( $table, 'client_id', 'clients(id)', 'CASCADE', 'CASCADE' ) );


			//create webservice API-key
			$key = $this->_generate_key();
			// If no key level provided, provide a generic key
			$client = $this->_get_clientId( "admin_client", "p@ssW0rDd" );
			$this->_insert_key( $key, array(
				'client_id'     => $client->id,
				'level'         => 10,
				'ignore_limits' => 1
			));
		}

		public function down () {
			$table = config_item( 'rest_key_column' );
			if ( $this->db->table_exists( $table ) ) {
				$this->db->query( drop_foreign_key( $table, 'client_id' ) );
				$this->dbforge->drop_table( $table );
			}
		}

		//private functions

		private function _generate_key () {
			do {
				// Generate a random salt
				$salt = base_convert( bin2hex( $this->security->get_random_bytes( 64 ) ), 16, 36 );
				// If an error occurred, then fall back to the previous method
				if ( $salt === FALSE ) {
					$salt = hash( 'sha256', time() . mt_rand() );
				}
				$new_key = substr( $salt, 0, config_item( 'rest_key_length' ) );
			} while ( $this->_key_exists( $new_key ) );
			return $new_key;
		}

		private function _key_exists ( $key ) {
			return $this->db->where( config_item( 'rest_key_column' ), $key )->count_all_results( config_item( 'rest_keys_table' ) ) > 0;
		}

		/* Private Data Methods */
		private function _get_clientId ( $username, $password ) {
			$query = $this->db->where( 'username', $username )->get( config_item( 'rest_client_table' ) )->row();

			return (password_verify( $password, $query->password )) ? $query : FALSE;
		}

		private function _insert_key ( $key, $data ) {
			$data[ config_item( 'rest_key_column' ) ] = $key;
			$data['date_created'] = function_exists( 'now' ) ? now() : time();
			return $this->db->set( $data )->insert( config_item( 'rest_keys_table' ) );
		}
	}
