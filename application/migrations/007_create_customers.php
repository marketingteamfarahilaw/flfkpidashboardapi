<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_customers extends CI_Migration {

	var $table = 'customers';

	public function up () {
		$this->dbforge->add_field( array(
			'customer_id'              => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'customer_first_name'      => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			),
			'customer_last_name'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			),
			'customer_middle_name'     => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			),
			'customer_username'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '50',
				'unique'     => TRUE
			),
			'customer_password'        => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
			),
			'customer_email'           => array(
				'type'       => 'VARCHAR',
				'constraint' => '100',
				'unique'     => TRUE
			),
			'customer_mobile'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '20',
				'unique'     => TRUE
			),
			'customer_address'         => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'customer_gender'          => array(
				'type' => 'SET("Female", "Male")',
			),
			'customer_birthday'        => array(
				'type' => 'DATE',
			),
			'customer_verified'        => array(
				'type' => 'boolean'
			),
			'customer_image_url'       => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'customer_date_registered' => array(
				'type' => 'DATETIME',
			),
			'customer_status'          => array(
				'type'    => 'SET("Active", "Deactivated", "Blocked")',
				'default' => 'Active',
			)
		) );
		$this->dbforge->add_key( 'customer_id', TRUE );
		$this->dbforge->create_table( $this->table );

		$this->db->insert( $this->table, array(
			'customer_first_name'      => 'Aubrey',
			'customer_last_name'       => 'Leonidas',
			'customer_email'           => 'aubrey.leonidas@godigitalcorp.com',
			'customer_username'        => 'aubrey',
			'customer_password'        => password_hash( 'password', PASSWORD_BCRYPT ),
			'customer_mobile'          => '09123456789',
			'customer_address'         => '1086 Beverly Hills, Los Angeles, California',
			'customer_verified'        => TRUE,
			'customer_image_url'       => '',
			'customer_date_registered' => date( 'Y-m-d H:i:s' ),
			'customer_birthday'        => '1993-08-10'
		) );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
