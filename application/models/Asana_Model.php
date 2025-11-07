<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Asana_model extends CI_Model
{
    protected $table = 'asanakpi';

    public function upsert_by_title(array $data, $useWorkspaceScope = false)
    {
        // ✅ include the new columns here
        $allowed = [
            'title','notes','parent_id','permalink_url','completed_at','due_on','completed',
            'parent_name','performed_by','task_id','workspace_id','workspace_name',
            'output_count','brand','task_type','time_minutes'   // <-- added
        ];

        // keep only allowed keys
        $row = array_intersect_key($data, array_flip($allowed));

        if (empty($row['title'])) {
            return ['status' => 'skipped', 'reason' => 'missing_title'];
        }

        // normalize types (defensive — controller already does most of this)
        foreach (['output_count','time_minutes'] as $k) {
            if (array_key_exists($k, $row) && $row[$k] !== '' && $row[$k] !== null) {
                $row[$k] = (int)$row[$k];
            }
        }
        foreach (['brand','task_type'] as $k) {
            if (array_key_exists($k, $row)) {
                $row[$k] = ($row[$k] === '') ? null : $row[$k];
            }
        }
        if (isset($row['completed_at']) && $row['completed_at'] === '') $row['completed_at'] = null;
        if (isset($row['due_on']) && $row['due_on'] === '')             $row['due_on']       = null;

        // where uniqueness (title) or (workspace_id,title)
        $where = ['title' => $row['title']];
        if ($useWorkspaceScope && !empty($row['workspace_id'])) {
            $where['workspace_id'] = $row['workspace_id'];
        }

        $exists = $this->db->get_where($this->table, $where, 1)->row_array();

        if ($exists) {
            // avoid changing unique keys if you use composite index
            $updateRow = $row;
            unset($updateRow['title']); // keep title stable on update
            if ($useWorkspaceScope) unset($updateRow['workspace_id']);

            if (!empty($updateRow)) {
                $this->db->where($where)->update($this->table, $updateRow);
            }
            return ['status' => 'updated', 'id' => (int)$exists['id']];
        } else {
            $this->db->insert($this->table, $row);
            return ['status' => 'inserted', 'id' => (int)$this->db->insert_id()];
        }
    }

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
