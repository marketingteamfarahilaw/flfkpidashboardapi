<?php

class Asana_Model extends CI_Model
{
    
	var $table = 'asanakpi';
	var $id = 'id';


    public function upsert_by_title(array $data, $useWorkspaceScope = false)
    {
        // Normalize/allow only known columns
        $allowed = [
            'title','notes','parent_id','permalink_url','completed_at','due_on','completed',
            'parent_name','performed_by','task_id','workspace_id','workspace_name'
        ];
        $row = array_intersect_key($data, array_flip($allowed));

        if (empty($row['title'])) {
            return ['status' => 'skipped', 'reason' => 'missing_title'];
        }

        // Build where clause for uniqueness
        $where = ['title' => $row['title']];
        if ($useWorkspaceScope && !empty($row['workspace_id'])) {
            $where['workspace_id'] = $row['workspace_id'];
        }

        // Does it exist?
        $exists = $this->db->get_where($this->table, $where, 1)->row_array();

        if ($exists) {
            // Avoid updating the unique columns themselves if you use composite unique index.
            // Timestamps: accept either null or valid 'Y-m-d H:i:s'
            if (isset($row['completed_at']) && $row['completed_at'] === '') $row['completed_at'] = NULL;
            if (isset($row['due_on']) && $row['due_on'] === '') $row['due_on'] = NULL;

            $this->db->where($where)->update($this->table, $row);
            return ['status' => 'updated', 'id' => (int)$exists['id']];
        } else {
            // Insert new
            if (isset($row['completed_at']) && $row['completed_at'] === '') $row['completed_at'] = NULL;
            if (isset($row['due_on']) && $row['due_on'] === '') $row['due_on'] = NULL;

            $this->db->insert($this->table, $row);
            return ['status' => 'inserted', 'id' => (int)$this->db->insert_id()];
        }
    }

    /**
     * Bulk upsert; returns summary counts and per-item results.
     */
    public function bulk_upsert(array $items, $useWorkspaceScope = false)
    {
        $inserted = 0; $updated = 0; $skipped = 0; $results = [];
        $this->db->trans_start();

        foreach ($items as $i => $item) {
            $res = $this->upsert_by_title($item, $useWorkspaceScope);
            $results[] = $res + ['index' => $i];

            if ($res['status'] === 'inserted') $inserted++;
            elseif ($res['status'] === 'updated') $updated++;
            else $skipped++;
        }

        $this->db->trans_complete();
        return [
            'inserted' => $inserted,
            'updated'  => $updated,
            'skipped'  => $skipped,
            'results'  => $results,
            'ok'       => $this->db->trans_status()
        ];
    }
}
