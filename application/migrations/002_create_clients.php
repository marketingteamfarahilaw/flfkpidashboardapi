<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Migration_create_clients extends CI_Migration {
		protected $table = 'clients';

		public function up () {
			$fields = array(
				'id'         => array(
					'type'           => 'INT(11)',
					'auto_increment' => TRUE,
					'unsigned'       => TRUE,
				),
				'username'   => array(
					'type'   => 'VARCHAR(255)',
					'unique' => TRUE,
				),
				'password'   => array(
					'type' => 'VARCHAR(64)',
				),
				'name'       => array(
					'type' => 'VARCHAR(32)',
				),
				'created_at' => array(
					'type' => 'DATETIME',
				),
			);
			$this->dbforge->add_field( $fields );
			$this->dbforge->add_key( 'id', TRUE );
			$this->dbforge->create_table( $this->table, TRUE );

			$this->db->insert( $this->table, array(
				'username'   => "admin_client",
				'password'   => password_hash( 'p@ssW0rDd', PASSWORD_BCRYPT ),
				'name'       => "Admin Client",
				'created_at' => date( 'Y-m-d H:i:s' ),
			) );

			$this->db->insert( $this->table, array(
				'username'   => "web_portal",
				'password'   => password_hash( 'p@ssW0rDd', PASSWORD_BCRYPT ),
				'name'       => "Web Portal Access",
				'created_at' => date( 'Y-m-d H:i:s' ),
			) );

			$this->db->insert( $this->table, array(
				'username'   => "cms_access",
				'password'   => password_hash( 'p@ssW0rDd', PASSWORD_BCRYPT ),
				'name'       => "CMS Access",
				'created_at' => date( 'Y-m-d H:i:s' ),
			) );
		}

		public function down () {
			if ( $this->db->table_exists( $this->table ) ) {
				$this->dbforge->drop_table( $this->table );
			}
		}
	}
