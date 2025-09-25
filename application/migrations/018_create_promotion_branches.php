<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_promotion_branches extends CI_Migration {

	var $table = 'promotion_branches';

	public function up () {
		$this->dbforge->add_field( array(
			'promotion_branch_id'        => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'promotion_branch_promo_id'  => array(
				'type'       => 'MEDIUMINT',
				'constraint' => 8,
			),
			'promotion_branch_branch_id' => array(
				'type'       => 'MEDIUMINT',
				'constraint' => 8,
			)
		) );
		$this->dbforge->add_key( 'promotion_branch_id', TRUE );
		$this->dbforge->add_key( 'promotion_branch_promo_id' );
		$this->dbforge->add_key( 'promotion_branch_branch_id' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}

}
