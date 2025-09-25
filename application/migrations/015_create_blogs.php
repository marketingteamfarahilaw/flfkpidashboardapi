<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_blogs extends CI_Migration {

	var $table = 'blogs';

	public function up () {
		$this->dbforge->add_field( array(
			'blog_id'             => array(
				'type'           => 'MEDIUMINT',
				'constraint'     => 8,
				'unsigned'       => TRUE,
				'auto_increment' => TRUE
			),
			'blog_title'          => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'blog_content'        => array(
				'type' => 'TEXT',
			),
			'blog_author'         => array(
				'type'       => 'VARCHAR',
				'constraint' => '255',
			),
			'blog_date_created'   => array(
				'type' => 'DATETIME'
			),
			'blog_date_published' => array(
				'type' => 'DATETIME'
			),
			'blog_status'         => array(
				'type' => 'SET("published", "draft")',
				'default' => 'draft'
			)
		) );
		$this->dbforge->add_key( 'blog_id', TRUE );
		$this->dbforge->add_key( 'blog_name' );
		$this->dbforge->add_key( 'blog_status' );
		$this->dbforge->create_table( $this->table );

	}

	public function down () {
		$this->dbforge->drop_table( $this->table );
	}
}
